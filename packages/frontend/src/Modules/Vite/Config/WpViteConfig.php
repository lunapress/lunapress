<?php
declare(strict_types=1);

namespace LunaPress\Frontend\Modules\Vite\Config;

use LunaPress\FrontendContracts\Vite\IViteConfig;
use LunaPress\CoreContracts\Plugin\IConfig;

defined('ABSPATH') || exit;

final readonly class WpViteConfig implements IViteConfig
{
    public function __construct(
        private IConfig $coreConfig,
    ) {
    }

    public function getBuildVitePath(): string
    {
        return rtrim($this->coreConfig->getPluginPath(), '/') . '/assets/dist/vite/';
    }

    public function getBuildViteUrl(): string
    {
        return rtrim($this->coreConfig->getPluginUrl(), '/') . '/assets/dist/vite/';
    }

    public function getPluginVersion(): string
    {
        return $this->coreConfig->getPluginVersion();
    }
}
