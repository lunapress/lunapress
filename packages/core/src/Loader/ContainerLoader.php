<?php
declare(strict_types=1);

namespace LunaPress\Core\Loader;

use LunaPress\Core\DiProvider;
use LunaPress\Core\Plugin\AbstractPlugin;
use LunaPress\CoreContracts\Plugin\IPlugin;
use LunaPress\CoreContracts\Support\ILoader;
use LunaPress\FoundationContracts\Container\IContainerBuilder;
use LunaPress\FoundationContracts\PackageMeta\IPackageMetaFactory;
use LunaPress\FoundationContracts\ServicePackage\IServicePackageMeta;
use LunaPress\FoundationContracts\Support\IHasDi;
use Override;

defined('ABSPATH') || exit;

final readonly class ContainerLoader implements ILoader
{
    private const string DI_CACHE_DIR        = 'cache/di';
    private const string NO_CACHE_FILE       = '.nocache';
    private const string DISABLE_CACHE_CONST = 'LUNAPRESS_DISABLE_CACHE';

    public function __construct(
        private AbstractPlugin $plugin,
        private IContainerBuilder $builder,
        private IPackageMetaFactory $metaFactory,
    ) {
    }

    #[Override]
    public function load(): void
    {
        $this->configureCache($this->plugin, $this->builder);

        // Core
        $this->addDiFile(DiProvider::class);

        // Service Packages
        foreach ($this->metaFactory->createAll() as $meta) {
            if ($meta instanceof IServicePackageMeta && $meta->getDiPath()) {
                $this->builder->addDefinitions($meta->getDiPath());
            }
        }

        // Packages
        foreach ($this->plugin->getPackages() as $package) {
            $this->addDiFile(is_string($package) ? $package : $package::class);
        }

        // Plugin
        $this->addDiFile($this->plugin::class);
        $this->builder->addDefinitions([
            IPlugin::class => $this->plugin,
        ]);

        $container = $this->builder->build();

        $this->plugin->setContainer($container);
    }

    /**
     * @param class-string<IHasDi> $class
     */
    private function addDiFile(string $class): void
    {
        $path = $class::getDiPath();
        if ($path && file_exists($path)) {
            $this->builder->addDefinitions($path);
        }
    }

    private function configureCache(AbstractPlugin $plugin, IContainerBuilder $builder): void
    {
        $pluginDirRaw = dirname($plugin->getCallerFile());
        $cacheDir     = $pluginDirRaw . '/' . self::DI_CACHE_DIR;
        $pluginDir    = strtoupper(str_replace('-', '_', basename($pluginDirRaw)));
        $disableConst = self::DISABLE_CACHE_CONST . '_' . $pluginDir;

        $noCacheFileExists  = file_exists($pluginDirRaw . '/' . self::NO_CACHE_FILE);
        $disableConstIsTrue = defined($disableConst) && constant($disableConst) === true;

        if ($noCacheFileExists || $disableConstIsTrue) {
            $builder->disableCache();
            return;
        }

        $builder->enableCache($cacheDir);
    }
}
