<?php
declare(strict_types=1);

use LunaPress\Frontend\Modules\Vite\Config\WpViteConfig;
use LunaPress\Frontend\Modules\Vite\Entity\WpViteEntry;
use LunaPress\Frontend\Modules\Vite\Entity\WpViteManifest;
use LunaPress\Frontend\Modules\Vite\Service\ConstantViteModeDetector;
use LunaPress\Frontend\Modules\Vite\Service\WpViteAssetsLoader;
use LunaPress\Frontend\Modules\Vite\Service\WpViteManifestReader;
use LunaPress\FrontendContracts\Vite\IViteAssetsLoader;
use LunaPress\FrontendContracts\Vite\IViteConfig;
use LunaPress\FrontendContracts\Vite\IViteEntry;
use LunaPress\FrontendContracts\Vite\IViteManifest;
use LunaPress\FrontendContracts\Vite\IViteManifestReader;
use LunaPress\FrontendContracts\Vite\IViteModeDetector;
use function DI\autowire;

return [
    IViteConfig::class => autowire(WpViteConfig::class),

    IViteEntry::class => autowire(WpViteEntry::class),
    IViteManifest::class => autowire(WpViteManifest::class),

    IViteModeDetector::class => autowire(ConstantViteModeDetector::class),
    IViteAssetsLoader::class => autowire(WpViteAssetsLoader::class),
    IViteManifestReader::class => autowire(WpViteManifestReader::class),
];
