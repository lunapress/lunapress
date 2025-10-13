<?php
declare(strict_types=1);

namespace LunaPress\Core\Loader;

use LunaPress\Core\Plugin\AbstractPlugin;
use LunaPress\CoreContracts\Support\ILoader;
use LunaPress\Foundation\PackageMeta\PackageMetaFactory;
use LunaPress\FoundationContracts\Container\IContainerBuilder;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

defined('ABSPATH') || exit;

final readonly class PluginLoader implements ILoader
{
    public function __construct(
        private AbstractPlugin $plugin,
        private IContainerBuilder $builder,
    ) {
    }

    /**
     * @return void
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
