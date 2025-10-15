<?php
declare(strict_types=1);

use LunaPress\Frontend\Modules\Vite\Config\WpViteConfig;
use LunaPress\Frontend\Modules\Vite\Service\ConstantViteModeDetector;
use LunaPress\Frontend\Modules\Vite\Service\WpViteAssetsLoader;
use LunaPress\Frontend\Modules\Vite\Service\WpViteManifestReader;
use LunaPress\FrontendContracts\Vite\IViteAssetsLoader;
use LunaPress\FrontendContracts\Vite\IViteConfig;
use LunaPress\FrontendContracts\Vite\IViteManifestReader;
use LunaPress\FrontendContracts\Vite\IViteModeDetector;
use function LunaPress\Foundation\Container\autowire;

return [
    IViteConfig::class => autowire(WpViteConfig::class),

    IViteModeDetector::class => autowire(ConstantViteModeDetector::class),
    IViteAssetsLoader::class => autowire(WpViteAssetsLoader::class),
    IViteManifestReader::class => autowire(WpViteManifestReader::class),
];
