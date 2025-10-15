<?php
declare(strict_types=1);

use LunaPress\Core\Hook\ActionManager;
use LunaPress\Core\Hook\FilterManager;
use LunaPress\Core\Subscriber\SubscriberRegistry;
use LunaPress\Core\Plugin\PluginConfig;
use LunaPress\Core\Plugin\PluginConfigFactory;
use LunaPress\Core\Plugin\PluginContext;
use LunaPress\Core\Plugin\PluginContextFactory;
use LunaPress\Core\Support\WpFunction\WpFunctionExecutor;
use LunaPress\Core\View\DefaultTemplateContextProvider;
use LunaPress\Core\View\TemplateManager;
use LunaPress\CoreContracts\Hook\IActionManager;
use LunaPress\CoreContracts\Hook\IFilterManager;
use LunaPress\CoreContracts\Subscriber\ISubscriberRegistry;
use LunaPress\CoreContracts\Plugin\IConfig;
use LunaPress\CoreContracts\Plugin\IConfigFactory;
use LunaPress\CoreContracts\Plugin\IPluginContext;
use LunaPress\CoreContracts\Plugin\IPluginContextFactory;
use LunaPress\CoreContracts\Support\WpFunction\IWpFunctionExecutor;
use LunaPress\FoundationContracts\View\ITemplateContextProvider;
use LunaPress\FoundationContracts\View\ITemplateManager;
use function LunaPress\Foundation\Container\autowire;
use function LunaPress\Foundation\Container\factory;

return [
    IConfig::class => autowire(PluginConfig::class),
    IConfigFactory::class => autowire(PluginConfigFactory::class),
    IPluginContext::class => autowire(PluginContext::class),
    IPluginContextFactory::class => autowire(PluginContextFactory::class),

    IActionManager::class => autowire(ActionManager::class),
    IFilterManager::class => autowire(FilterManager::class),
    ISubscriberRegistry::class => autowire(SubscriberRegistry::class),

    IWpFunctionExecutor::class => autowire(WpFunctionExecutor::class),

    ITemplateContextProvider::class => autowire(DefaultTemplateContextProvider::class),
    ITemplateManager::class => factory(function (
        ITemplateContextProvider $provider,
        IConfig $config
    ): TemplateManager {
        return (new TemplateManager($provider))
            ->setBasePath($config->getPluginPath() . '/templates');
    }),
];
