<?php
declare(strict_types=1);

namespace LunaPress\Core\Plugin;

use Exception;
use LunaPress\CoreContracts\Plugin\IPluginContext;
use LunaPress\FoundationContracts\Container\IContainerBuilder;
use LunaPress\FoundationContracts\Module\IModule;
use LunaPress\FoundationContracts\Package\IPackage;
use LunaPress\CoreContracts\Plugin\IConfig;
use LunaPress\CoreContracts\Plugin\IConfigFactory;
use LunaPress\CoreContracts\Plugin\IPluginContextFactory;
use LunaPress\CoreContracts\Subscriber\ISubscriberRegistry;
use LunaPress\CoreContracts\Plugin\IPlugin;
use LunaPress\FoundationContracts\Support\HasDi;
use LunaPress\Core\DiProvider;
use LunaPress\Foundation\Support\Singleton;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionClass;
use RuntimeException;
use function LunaPress\Foundation\Container\autowire;
use function LunaPress\Foundation\Container\factory;

defined('ABSPATH') || exit;

abstract class AbstractPlugin extends Singleton implements IPlugin
{
    protected ContainerInterface $container;
    protected ISubscriberRegistry $subscriberRegistry;
    protected ?IContainerBuilder $containerBuilder = null;
    private bool $initialized                      = false;

    /**
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function boot(): void
    {
        if (!$this->initialized) {
            if (!$this->containerBuilder) {
                throw new RuntimeException(
                    'No DI container builder provided. ' .
                    'Call ->setContainerBuilder() before boot().'
                );
            }

            $this->init();
        }

        $this->registerLifecycle();

        $this->registerModules($this->getModules());
        $this->registerPackages($this->getPackages());
    }

    public function setContainerBuilder(IContainerBuilder $builder): self
    {
        $this->containerBuilder = $builder;

        return $this;
    }

    public function activate(): void {
        /** @var IPluginContext $context */
        $context = $this->container->get(IPluginContext::class);

        $this->iteratePackages(function (IPackage $package) use ($context): void {
            $package->activate($context);
        });
    }

    public function deactivate(): void {
        /** @var IPluginContext $context */
        $context = $this->container->get(IPluginContext::class);

        $this->iteratePackages(function (IPackage $package) use ($context): void {
            $package->deactivate($context);
        });
    }


    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function registerModule(IModule|string $module): void
    {
        if (is_string($module)) {
            $module = $this->container->get($module);
        }

        $this->subscriberRegistry->registerMany($module->subscribers());
    }

    /**
     * @param array $modules
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function registerModules(array $modules): void
    {
        foreach ($modules as $module) {
            $this->registerModule($module);
        }
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function registerPackage(IPackage|string $package): void
    {
        if (is_string($package)) {
            $package = $this->container->get($package);
        }

        $this->registerModules($package->getModules());
    }

    /**
     * @param array $packages
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function registerPackages(array $packages): void
    {
        foreach ($packages as $package) {
            $this->registerPackage($package);
        }
    }

    public static function getDiPath(): ?string
    {
        $ref = new ReflectionClass(static::class);
        $dir = dirname($ref->getFileName());

        $path = $dir . '/di.php';

        return file_exists($path) ? $path : null;
    }

    private function registerLifecycle(): void
    {
        $ref  = new ReflectionClass(static::class);
        $file = $ref->getFileName();

        register_activation_hook($file, $this->activate(...));
        register_deactivation_hook($file, $this->deactivate(...));
    }

    /**
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    protected function init(): void
    {
        $builder = $this->containerBuilder;

        // Core
        $this->addDiFile($builder, DiProvider::class);

        // Plugin
        $builder->addDefinitions([
            IConfigFactory::class => autowire(PluginConfigFactory::class),
            IConfig::class => factory(function (IConfigFactory $factory) {
                return $factory->make($this);
            }),

            IPluginContextFactory::class => autowire(PluginContextFactory::class),
            IPluginContext::class => factory(fn (PluginContextFactory $factory) => $factory->make($this)),
        ]);
        $this->addDiFile($builder, static::class);

        // Packages
        $this->iteratePackages(function (IPackage $package) use ($builder): void {
            $this->addDiFile($builder, $package::class);
        });

        $this->container          = $builder->build();
        $this->subscriberRegistry = $this->container->get(ISubscriberRegistry::class);

        $this->initialized = true;
    }

    /**
     * @param IContainerBuilder    $builder
     * @param class-string<HasDi> $class
     */
    private function addDiFile(IContainerBuilder $builder, string $class): void
    {
        $path = $class::getDiPath();
        if ($path && file_exists($path)) {
            $builder->addDefinitions($path);
        }
    }

    /**
     * @param callable(IPackage): void $callback
     */
    private function iteratePackages(callable $callback): void
    {
        foreach ($this->getPackages() as $package) {
            if (is_string($package)) {
                if (!class_exists($package) || !is_a($package, IPackage::class, true)) {
                    continue;
                }

                $package = new $package();
            }

            if ($package instanceof IPackage) {
                $callback($package);
            }
        }
    }
}
