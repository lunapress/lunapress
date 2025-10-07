<?php
declare(strict_types=1);

namespace LunaPress\Core\Plugin;

use LunaPress\CoreContracts\Plugin\IConfig;

defined('ABSPATH') || exit;

final readonly class PluginConfig implements IConfig
{
    public function __construct(
        private string $pluginVersion,
        private string $pluginPath,
        private string $pluginUrl,
    ) {
    }

    public function getPluginVersion(): string
    {
        return $this->pluginVersion;
    }

    public function getPluginPath(): string
    {
        return $this->pluginPath;
    }

    public function getPluginUrl(): string
    {
        return $this->pluginUrl;
    }
}
