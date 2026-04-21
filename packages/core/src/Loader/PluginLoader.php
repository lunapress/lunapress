<?php

declare(strict_types=1);

namespace LunaPress\Core\Loader;

use LunaPress\Core\Plugin\AbstractPlugin;
use LunaPress\Foundation\PackageMeta\PackageMetaFactory;
use LunaPress\FoundationContracts\Container\IContainerBuilder;
use LunaPress\FoundationContracts\Support\ILoader;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

final readonly class PluginLoader implements ILoader
{
    public function __construct(
        private AbstractPlugin $plugin,
        private IContainerBuilder $builder,
    ) {
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function load(): void
    {
        (new ContainerLoader($this->plugin, $this->builder, new PackageMetaFactory()))->load();

        $container = $this->plugin->getContainer();

        (new PackageLoader($this->plugin, $container))->load();
        (new ModuleLoader($this->plugin, $container))->load();
        (new LifecycleLoader($this->plugin))->load();
    }
}
