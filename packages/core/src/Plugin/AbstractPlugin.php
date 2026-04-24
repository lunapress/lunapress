<?php

declare(strict_types=1);

namespace LunaPress\Core\Plugin;

use LunaPress\Core\Loader\ContainerFactory;
use LunaPress\Core\Loader\PluginBootstrapper;
use LunaPress\Core\Support\PackageIterator;
use LunaPress\CoreContracts\Plugin\Plugin;
use LunaPress\CoreContracts\Plugin\PluginContext;
use LunaPress\Foundation\Support\Singleton;
use LunaPress\FoundationContracts\Container\ContainerBuilder;
use LunaPress\FoundationContracts\Package\Package;
use Override;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionClass;
use RuntimeException;
use function dirname;
use function file_exists;
use function is_string;

abstract class AbstractPlugin extends Singleton implements Plugin
{
    protected string $callerFile;
    protected ContainerInterface $container;
    protected ?ContainerBuilder $containerBuilder = null;
    private bool $booted = false;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    #[Override]
    public function boot(string $callerFile): void
    {
        if ($this->booted) {
            return;
        }

        if (!$this->containerBuilder) {
            throw new RuntimeException(
                'No DI container builder provided. ' .
                'Call ->setContainerBuilder() before boot().'
            );
        }

        $this->callerFile = $callerFile;

        $this->container = (new ContainerFactory($this->containerBuilder))->make($this);

        (new PluginBootstrapper($this->container))->boot($this);

        $this->booted = true;
    }

    #[Override]
    public function setContainerBuilder(ContainerBuilder $builder): self
    {
        $this->containerBuilder = $builder;

        return $this;
    }

    #[Override]
    public function activate(): void {
        /** @var PluginContext $context */
        $context = $this->container->get(PluginContext::class);

        foreach ($this->getPackages() as $package) {
            $instance = is_string($package) ? $this->container->get($package) : $package;

            if (!($instance instanceof Package)) {
				continue;
			}

			$instance->activate($context->context);
        }
    }

    #[Override]
    public function deactivate(): void {
        /** @var PluginContext $context */
        $context = $this->container->get(PluginContext::class);

        foreach ($this->getPackages() as $package) {
            $instance = is_string($package) ? $this->container->get($package) : $package;

            if (!($instance instanceof Package)) {
				continue;
			}

			$instance->deactivate($context->context);
        }
    }

    #[Override]
    public static function getDiPath(): ?string
    {
        $ref = new ReflectionClass(static::class);
        $dir = dirname($ref->getFileName());

        $path = $dir . '/di.php';

        return file_exists($path) ? $path : null;
    }

    #[Override]
    public function getCallerFile(): string
    {
        return $this->callerFile;
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }
}
