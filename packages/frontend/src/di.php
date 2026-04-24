<?php

declare(strict_types=1);

defined('ABSPATH') || exit;

use LunaPress\Frontend\Modules\Vite\Service\DefaultViteAssetsLoader;
use LunaPress\Frontend\Modules\Vite\Service\DefaultViteConfigFactory;
use LunaPress\Frontend\Modules\Vite\Service\DefaultViteEnvDetector;
use LunaPress\Frontend\Modules\Vite\Service\DefaultViteManifestFactory;
use LunaPress\Frontend\Modules\Vite\Service\DefaultViteManifestReader;
use LunaPress\FrontendContracts\Vite\DTO\ViteConfig;
use LunaPress\FrontendContracts\Vite\ViteAssetsLoader;
use LunaPress\FrontendContracts\Vite\ViteConfigFactory;
use LunaPress\FrontendContracts\Vite\ViteEnvDetector;
use LunaPress\FrontendContracts\Vite\ViteManifestFactory;
use LunaPress\FrontendContracts\Vite\ViteManifestReader;
use function LunaPress\Foundation\Container\autowire;
use function LunaPress\Foundation\Container\factory;

return [
    ViteConfig::class => factory(function (ViteConfigFactory $factory) {
        return $factory->make();
    }),

    ViteEnvDetector::class => autowire(DefaultViteEnvDetector::class),
    ViteConfigFactory::class => autowire(DefaultViteConfigFactory::class),
    ViteAssetsLoader::class => autowire(DefaultViteAssetsLoader::class),
    ViteManifestReader::class => autowire(DefaultViteManifestReader::class),
    ViteManifestFactory::class => autowire(DefaultViteManifestFactory::class),
];
