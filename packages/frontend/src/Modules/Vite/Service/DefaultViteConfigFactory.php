<?php

declare(strict_types=1);

namespace LunaPress\Frontend\Modules\Vite\Service;

use LunaPress\CoreContracts\Plugin\PluginConfig;
use LunaPress\FrontendContracts\Vite\DTO\ViteConfig;
use LunaPress\FrontendContracts\Vite\Enum\ViteMode;
use LunaPress\FrontendContracts\Vite\ViteConfigFactory;
use LunaPress\FrontendContracts\Vite\ViteEnvDetector;
use function rtrim;

final readonly class DefaultViteConfigFactory implements ViteConfigFactory
{
    public const string DEFAULT_BUILD_PATH = '/assets/dist/vite/';

    public function __construct(
        private PluginConfig $pluginConfig,
        private ViteEnvDetector  $viteEnvDetector,
    ) {
    }

    public function make(): ViteConfig
    {
        return new ViteConfig(
            buildVitePath: rtrim($this->pluginConfig->pluginPath, '/') . self::DEFAULT_BUILD_PATH,
            buildViteUrl: rtrim($this->pluginConfig->pluginUrl, '/') . self::DEFAULT_BUILD_PATH,
            pluginVersion: $this->pluginConfig->pluginVersion,
            mode: $this->viteEnvDetector->isDev() ? ViteMode::DEV : ViteMode::PROD
        );
    }
}
