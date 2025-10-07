<?php
declare(strict_types=1);

namespace LunaPress\Core\Plugin;

use LunaPress\CoreContracts\Plugin\IContext;
use LunaPress\CoreContracts\Plugin\IContextFactory;
use LunaPress\CoreContracts\Plugin\IPlugin;
use ReflectionClass;

defined('ABSPATH') || exit;

class PluginContextFactory implements IContextFactory
{
    public function make(IPlugin $plugin): IContext
    {
        $ref       = new ReflectionClass($plugin);
        $namespace = $ref->getNamespaceName();
        $prefix    = $plugin->getPrefix();

        return new PluginContext($prefix, $namespace);
    }
}
