<?php

declare(strict_types=1);

namespace LunaPress\Core\Loader;

use LunaPress\Core\DiProvider;
use LunaPress\CoreContracts\Plugin\Plugin;
use LunaPress\Foundation\PackageMeta\DefaultPackageMetaFactory;
use LunaPress\Foundation\PackageMeta\DefaultPackageMetaProvider;
use LunaPress\FoundationContracts\Container\ContainerBuilder;
use LunaPress\FoundationContracts\PackageMeta\PackageMetaProvider;
use LunaPress\FoundationContracts\ServicePackage\ServicePackageMeta;
use LunaPress\FoundationContracts\Support\Factory;
use LunaPress\FoundationContracts\Support\HasDi;
use Psr\Container\ContainerInterface;
use function basename;
use function constant;
use function defined;
use function dirname;
use function file_exists;
use function is_string;
use function str_replace;
use function strtoupper;

final readonly class ContainerFactory implements Factory
{
    private const string DI_CACHE_DIR        = 'cache/di';
    private const string NO_CACHE_FILE       = '.nocache';
    private const string DISABLE_CACHE_CONST = 'LUNAPRESS_DISABLE_CACHE';

    public function __construct(
        private ContainerBuilder    $builder,
        private PackageMetaProvider $packageMetaProvider = new DefaultPackageMetaProvider(
            new DefaultPackageMetaFactory()
        ),
    ) {
    }

    public function make(Plugin $plugin): ContainerInterface
    {
        $this->configureCache($plugin);

        // Core
        $this->addDiFile(DiProvider::class);

        // Service Packages
        foreach ($this->packageMetaProvider->all() as $meta) {
            if (!($meta instanceof ServicePackageMeta) || $meta->diPath === null) {
                continue;
            }

            $this->builder->addDefinitions($meta->diPath);
        }

        // Packages
        foreach ($plugin->getPackages() as $package) {
            $this->addDiFile(is_string($package) ? $package : $package::class);
        }

        // Plugin
        $this->addDiFile($plugin::class);
        $this->builder->addDefinitions([
            Plugin::class => $plugin,
        ]);

        return $this->builder->build();
    }

    /**
     * @param class-string<HasDi> $class
     */
    private function addDiFile(string $class): void
    {
        $path = $class::getDiPath();

        if ($path === null || !file_exists($path)) {
			return;
		}

		$this->builder->addDefinitions($path);
    }

    private function configureCache(Plugin $plugin): void
    {
        $pluginDirRaw = dirname($plugin->getCallerFile());
        $cacheDir     = $pluginDirRaw . '/' . self::DI_CACHE_DIR;
        $pluginDir    = strtoupper(str_replace('-', '_', basename($pluginDirRaw)));
        $disableConst = self::DISABLE_CACHE_CONST . '_' . $pluginDir;

        $noCacheFileExists  = file_exists($pluginDirRaw . '/' . self::NO_CACHE_FILE);
        $disableConstIsTrue = defined($disableConst) && constant($disableConst) === true;

        if ($noCacheFileExists || $disableConstIsTrue) {
            $this->builder->disableCache();
            return;
        }

        $this->builder->enableCache($cacheDir);
    }
}
