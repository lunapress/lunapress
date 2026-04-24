<?php

declare(strict_types=1);

namespace LunaPress\Core\Plugin;

use LunaPress\CoreContracts\Plugin\Plugin;
use LunaPress\CoreContracts\Plugin\PluginConfig;
use LunaPress\CoreContracts\Plugin\PluginConfigFactory;

final readonly class DefaultPluginConfigFactory implements PluginConfigFactory
{
    public function make(Plugin $plugin): PluginConfig
    {
        $file = $plugin->getCallerFile();

        $pluginPath = untrailingslashit(plugin_dir_path($file));
        $pluginUrl  = untrailingslashit(plugin_dir_url($file));

        $pluginData = get_plugin_data($file, false, false);
        $version    = $pluginData['Version'];

        return new PluginConfig(
            $version,
            $pluginPath,
            $pluginUrl
        );
    }
}
