<?php
declare(strict_types=1);

namespace LunaPress\Core\Plugin;

use LunaPress\CoreContracts\Plugin\IPluginContext;
use LunaPress\CoreContracts\Plugin\IPluginContextFactory;
use LunaPress\CoreContracts\Plugin\IPlugin;
use ReflectionClass;

defined('ABSPATH') || exit;

final class PluginContextFactory implements IPluginContextFactory
{
    public function make(IPlugin $plugin): IPluginContext
    {
        $ref       = new ReflectionClass($plugin);
        $namespace = $ref->getNamespaceName();
        $prefix    = $plugin->getPrefix();

        return new PluginContext($prefix, $namespace);
    }
}
