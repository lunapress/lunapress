<?php
declare(strict_types=1);

namespace LunaPress\Core\Loader;

use LunaPress\Core\DiProvider;
use LunaPress\Core\Plugin\AbstractPlugin;
use LunaPress\Core\Plugin\PluginConfigFactory;
use LunaPress\Core\Plugin\PluginContextFactory;
use LunaPress\CoreContracts\Plugin\IConfig;
use LunaPress\CoreContracts\Plugin\IConfigFactory;
use LunaPress\CoreContracts\Plugin\IPluginContext;
use LunaPress\CoreContracts\Plugin\IPluginContextFactory;
use LunaPress\CoreContracts\Support\ILoader;
use LunaPress\FoundationContracts\Container\IContainerBuilder;
use LunaPress\FoundationContracts\Support\HasDi;
use function LunaPress\Foundation\Container\autowire;
use function LunaPress\Foundation\Container\factory;

defined('ABSPATH') || exit;

final readonly class ContainerLoader implements ILoader
{
    public function __construct(
        private AbstractPlugin $plugin,
        private IContainerBuilder $builder,
    ) {
    }

    public function load(): void
    {
        // Core
        $this->addDiFile(DiProvider::class);

        // Plugin
        $this->builder->addDefinitions([
            IConfigFactory::class => autowire(PluginConfigFactory::class),
            IConfig::class => factory(function (IConfigFactory $factory) {
                return $factory->make($this->plugin);
            }),

            IPluginContextFactory::class => autowire(PluginContextFactory::class),
            IPluginContext::class => factory(fn (PluginContextFactory $factory) => $factory->make($this->plugin)),
        ]);
        $this->addDiFile($this->plugin::class);

        // Packages
        foreach ($this->plugin->getPackages() as $package) {
            $this->addDiFile(is_string($package) ? $package : $package::class);
        }

        $this->plugin->setContainer($this->builder->build());
    }

    /**
     * @param class-string<HasDi> $class
     */
    private function addDiFile(string $class): void
    {
        $path = $class::getDiPath();
        if ($path && file_exists($path)) {
            $this->builder->addDefinitions($path);
        }
    }
}
