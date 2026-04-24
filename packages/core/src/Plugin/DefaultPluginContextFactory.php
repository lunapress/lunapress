<?php

declare(strict_types=1);

namespace LunaPress\Core\Plugin;

use LunaPress\CoreContracts\Plugin\Plugin;
use LunaPress\CoreContracts\Plugin\PluginContext;
use LunaPress\CoreContracts\Plugin\PluginContextFactory;
use LunaPress\FoundationContracts\Plugin\Context;
use ReflectionClass;

final class DefaultPluginContextFactory implements PluginContextFactory
{
    public function make(Plugin $plugin): PluginContext
    {
        $ref       = new ReflectionClass($plugin);
        $namespace = $ref->getNamespaceName();
        $prefix    = $plugin->getPrefix();

        return new PluginContext(
            new Context(
                $prefix,
                $namespace
            )
        );
    }
}
