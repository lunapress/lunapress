<?php
declare(strict_types=1);

namespace LunaPress\Core\Plugin;

use LunaPress\CoreContracts\Plugin\IConfig;
use LunaPress\CoreContracts\Plugin\IConfigFactory;
use LunaPress\CoreContracts\Plugin\IPlugin;
use ReflectionClass;
use RuntimeException;

defined('ABSPATH') || exit;

final readonly class PluginConfigFactory implements IConfigFactory
{
    public function make(IPlugin $plugin): IConfig
    {
        $reflection = new ReflectionClass($plugin);
        $file       = $reflection->getFileName();

        if ($file === false) {
            throw new RuntimeException('Unable to resolve plugin file path.');
        }

        $pluginPath = untrailingslashit(plugin_dir_path($file));
        $pluginUrl  = untrailingslashit(plugin_dir_url($file));

        $pluginData = get_plugin_data($file, false, false);
        $version    = $pluginData['Version'] ?? '1.0.0';

        return new PluginConfig(
            $version,
            $pluginPath,
            $pluginUrl
        );
    }
}
