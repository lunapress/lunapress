<?php

declare(strict_types=1);

defined('ABSPATH') || exit;

use LunaPress\Core\Hook\DefaultActionManager;
use LunaPress\Core\Hook\DefaultFilterManager;
use LunaPress\Core\Plugin\DefaultPluginConfigFactory;
use LunaPress\Core\Plugin\DefaultPluginContextFactory;
use LunaPress\Core\Subscriber\DefaultSubscriberRegistry;
use LunaPress\Core\View\DefaultTemplateContextProvider;
use LunaPress\Core\View\DefaultTemplateManager;
use LunaPress\CoreContracts\Hook\ActionManager;
use LunaPress\CoreContracts\Hook\FilterManager;
use LunaPress\CoreContracts\Plugin\Plugin;
use LunaPress\CoreContracts\Plugin\PluginConfig;
use LunaPress\CoreContracts\Plugin\PluginConfigFactory;
use LunaPress\CoreContracts\Plugin\PluginContext;
use LunaPress\CoreContracts\Plugin\PluginContextFactory;
use LunaPress\CoreContracts\Subscriber\SubscriberRegistry;
use LunaPress\Foundation\Support\DefaultWpCaster;
use LunaPress\FoundationContracts\Support\Wp\WpCaster;
use LunaPress\FoundationContracts\View\TemplateContextProvider;
use LunaPress\FoundationContracts\View\TemplateManager;
use function LunaPress\Foundation\Container\autowire;
use function LunaPress\Foundation\Container\factory;

return [
    PluginConfigFactory::class => autowire(DefaultPluginConfigFactory::class),
    PluginContextFactory::class => autowire(DefaultPluginContextFactory::class),
    PluginConfig::class => factory(function (PluginConfigFactory $factory, Plugin $plugin) {
        return $factory->make($plugin);
    }),
    PluginContext::class => factory(function (DefaultPluginContextFactory $factory, Plugin $plugin) {
        return $factory->make($plugin);
    }),

    ActionManager::class => autowire(DefaultActionManager::class),
    FilterManager::class => autowire(DefaultFilterManager::class),
    SubscriberRegistry::class => autowire(DefaultSubscriberRegistry::class),

    WpCaster::class => autowire(DefaultWpCaster::class),

    TemplateContextProvider::class => autowire(DefaultTemplateContextProvider::class),
    TemplateManager::class => factory(function (
        TemplateContextProvider $provider,
        PluginConfig $config
    ): DefaultTemplateManager {
        return (new DefaultTemplateManager($provider))
            ->setBasePath($config->pluginPath . '/templates');
    }),
];
