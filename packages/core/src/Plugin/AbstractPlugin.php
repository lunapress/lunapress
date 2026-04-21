<?php

declare(strict_types=1);

namespace LunaPress\Core\Plugin;

use LunaPress\Core\Loader\PluginLoader;
use LunaPress\Core\Support\PackageIterator;
use LunaPress\CoreContracts\Plugin\IPlugin;
use LunaPress\CoreContracts\Plugin\IPluginContext;
use LunaPress\Foundation\Support\Singleton;
use LunaPress\FoundationContracts\Container\IContainerBuilder;
use LunaPress\FoundationContracts\Package\IPackage;
use Override;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionClass;
use RuntimeException;
use function dirname;
use function file_exists;

abstract class AbstractPlugin extends Singleton implements IPlugin
{
    use PackageIterator;

    protected string $callerFile;
    protected ContainerInterface $container;
    protected ?IContainerBuilder $containerBuilder = null;
    private bool $initialized                      = false;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    #[Override]
    public function boot(string $callerFile): void
    {
        if ($this->initialized) {
            return;
        }

        $this->callerFile = $callerFile;

        if (!$this->containerBuilder) {
            throw new RuntimeException(
                'No DI container builder provided. ' .
                'Call ->setContainerBuilder() before boot().'
            );
        }

        (new PluginLoader($this, $this->containerBuilder))->load();

        $this->initialized = true;
    }

    #[Override]
    public function setContainerBuilder(IContainerBuilder $builder): self
    {
        $this->containerBuilder = $builder;

        return $this;
    }

    #[Override]
    public function activate(): void {
        /** @var IPluginContext $context */
        $context = $this->container->get(IPluginContext::class);

        $this->iteratePackages($this->getPackages(), function (IPackage $package) use ($context): void {
            $package->activate($context);
        });
    }

    #[Override]
    public function deactivate(): void {
        /** @var IPluginContext $context */
        $context = $this->container->get(IPluginContext::class);

        $this->iteratePackages($this->getPackages(), function (IPackage $package) use ($context): void {
            $package->deactivate($context);
        });
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

    public function setContainer(ContainerInterface $container): self
    {
        $this->container = $container;

        return $this;
    }

    public function getContainerBuilder(): ?IContainerBuilder
    {
        return $this->containerBuilder;
    }
}
