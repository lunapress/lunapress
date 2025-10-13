<?php
declare(strict_types=1);

namespace LunaPress\Core\Plugin;

use LunaPress\Core\Loader\PluginLoader;
use LunaPress\Core\Support\PackageIterator;
use LunaPress\CoreContracts\Plugin\IPluginContext;
use LunaPress\FoundationContracts\Container\IContainerBuilder;
use LunaPress\FoundationContracts\Package\IPackage;
use LunaPress\CoreContracts\Plugin\IPlugin;
use LunaPress\Foundation\Support\Singleton;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionClass;
use RuntimeException;

defined('ABSPATH') || exit;

abstract class AbstractPlugin extends Singleton implements IPlugin
{
    use PackageIterator;

    protected ContainerInterface $container;
    protected ?IContainerBuilder $containerBuilder = null;
    private bool $initialized                      = false;

    /**
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function boot(): void
    {
        if ($this->initialized) {
            return;
        }

        if (!$this->containerBuilder) {
            throw new RuntimeException(
                'No DI container builder provided. ' .
                'Call ->setContainerBuilder() before boot().'
            );
        }

        (new PluginLoader($this, $this->containerBuilder))->load();

        $this->initialized = true;
    }

    public function setContainerBuilder(IContainerBuilder $builder): self
    {
        $this->containerBuilder = $builder;

        return $this;
    }

    public function activate(): void {
        /** @var IPluginContext $context */
        $context = $this->container->get(IPluginContext::class);

        $this->iteratePackages($this->getPackages(), function (IPackage $package) use ($context): void {
            $package->activate($context);
        });
    }

    public function deactivate(): void {
        /** @var IPluginContext $context */
        $context = $this->container->get(IPluginContext::class);

        $this->iteratePackages($this->getPackages(), function (IPackage $package) use ($context): void {
            $package->deactivate($context);
        });
    }

    public static function getDiPath(): ?string
    {
        $ref = new ReflectionClass(static::class);
        $dir = dirname($ref->getFileName());

        $path = $dir . '/di.php';

        return file_exists($path) ? $path : null;
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * @param ContainerInterface $container
     * @return self
     */
    public function setContainer(ContainerInterface $container): self
    {
        $this->container = $container;

        return $this;
    }

    /**
     * @return IContainerBuilder|null
     */
    public function getContainerBuilder(): ?IContainerBuilder
    {
        return $this->containerBuilder;
    }
}
