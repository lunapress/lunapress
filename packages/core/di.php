<?php
declare(strict_types=1);

use LunaPress\Core\Hook\ActionManager;
use LunaPress\Core\Hook\FilterManager;
use LunaPress\Core\Hook\SubscriberRegistry;
use LunaPress\Core\Plugin\PluginConfig;
use LunaPress\Core\Plugin\PluginConfigFactory;
use LunaPress\Core\Plugin\PluginContext;
use LunaPress\Core\Plugin\PluginContextFactory;
use LunaPress\Core\Support\WpFunction\WpFunctionExecutor;
use LunaPress\CoreContracts\Hook\IActionManager;
use LunaPress\CoreContracts\Hook\IFilterManager;
use LunaPress\CoreContracts\Hook\ISubscriberRegistry;
use LunaPress\CoreContracts\Plugin\IConfig;
use LunaPress\CoreContracts\Plugin\IConfigFactory;
use LunaPress\CoreContracts\Plugin\IContext;
use LunaPress\CoreContracts\Plugin\IContextFactory;
use LunaPress\CoreContracts\Support\WpFunction\IWpFunctionExecutor;
use function DI\autowire;

return [
    IConfig::class => autowire(PluginConfig::class),
    IConfigFactory::class => autowire(PluginConfigFactory::class),
    IContext::class => autowire(PluginContext::class),
    IContextFactory::class => autowire(PluginContextFactory::class),

    IActionManager::class => autowire(ActionManager::class),
    IFilterManager::class => autowire(FilterManager::class),
    ISubscriberRegistry::class => autowire(SubscriberRegistry::class),

    IWpFunctionExecutor::class => autowire(WpFunctionExecutor::class),
];
