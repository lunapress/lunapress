<?php
declare(strict_types=1);

use LunaPress\Wp\Assets\AssetDependency;
use LunaPress\Wp\Assets\AssetDependencyFactory;
use LunaPress\Wp\Assets\WpEnqueueScript\WpEnqueueScript;
use LunaPress\Wp\Assets\WpEnqueueScript\WpEnqueueScriptArgs;
use LunaPress\Wp\Assets\WpEnqueueScript\WpEnqueueScriptArgsFactory;
use LunaPress\Wp\Assets\WpEnqueueScript\WpEnqueueScriptFactory;
use LunaPress\Wp\Assets\WpEnqueueScriptModule\WpEnqueueScriptModule;
use LunaPress\Wp\Assets\WpEnqueueScriptModule\WpEnqueueScriptModuleFactory;
use LunaPress\Wp\Assets\WpEnqueueScriptModule\WpEnqueueScriptModuleDep;
use LunaPress\Wp\Assets\WpEnqueueScriptModule\WpEnqueueScriptModuleDepsFactory;
use LunaPress\Wp\Assets\WpEnqueueStyle\WpEnqueueStyle;
use LunaPress\Wp\Assets\WpEnqueueStyle\WpEnqueueStyleFactory;
use LunaPress\Wp\Assets\WpRegisterScript\WpRegisterScript;
use LunaPress\Wp\Assets\WpRegisterScript\WpRegisterScriptArgs;
use LunaPress\Wp\Assets\WpRegisterScript\WpRegisterScriptArgsFactory;
use LunaPress\Wp\Assets\WpRegisterScript\WpRegisterScriptFactory;
use LunaPress\Wp\AssetsContracts\IAssetDependency;
use LunaPress\Wp\AssetsContracts\IAssetDependencyFactory;
use LunaPress\Wp\AssetsContracts\WpEnqueueScript\IWpEnqueueScriptArgs;
use LunaPress\Wp\AssetsContracts\WpEnqueueScript\IWpEnqueueScriptArgsFactory;
use LunaPress\Wp\AssetsContracts\WpEnqueueScript\IWpEnqueueScriptFactory;
use LunaPress\Wp\AssetsContracts\WpEnqueueScript\IWpEnqueueScriptFunction;
use LunaPress\Wp\AssetsContracts\WpEnqueueScriptModule\IWpEnqueueScriptModuleFactory;
use LunaPress\Wp\AssetsContracts\WpEnqueueScriptModule\IWpEnqueueScriptModuleDep;
use LunaPress\Wp\AssetsContracts\WpEnqueueScriptModule\IWpEnqueueScriptModuleDepFactory;
use LunaPress\Wp\AssetsContracts\WpEnqueueScriptModule\IWpEnqueueScriptModuleFunction;
use LunaPress\Wp\AssetsContracts\WpEnqueueStyle\IWpEnqueueStyleFactory;
use LunaPress\Wp\AssetsContracts\WpEnqueueStyle\IWpEnqueueStyleFunction;
use LunaPress\Wp\AssetsContracts\WpRegisterScript\IWpRegisterScriptArgs;
use LunaPress\Wp\AssetsContracts\WpRegisterScript\IWpRegisterScriptArgsFactory;
use LunaPress\Wp\AssetsContracts\WpRegisterScript\IWpRegisterScriptFactory;
use LunaPress\Wp\AssetsContracts\WpRegisterScript\IWpRegisterScriptFunction;
use function LunaPress\Foundation\Container\autowire;

return [
    IWpEnqueueScriptModuleFunction::class => autowire(WpEnqueueScriptModule::class),
    IWpEnqueueScriptModuleFactory::class => autowire(WpEnqueueScriptModuleFactory::class),
    IWpEnqueueScriptModuleDep::class => autowire(WpEnqueueScriptModuleDep::class),
    IWpEnqueueScriptModuleDepFactory::class => autowire(WpEnqueueScriptModuleDepsFactory::class),

    IWpEnqueueStyleFunction::class => autowire(WpEnqueueStyle::class),
    IWpEnqueueStyleFactory::class => autowire(WpEnqueueStyleFactory::class),

    IWpRegisterScriptFunction::class => autowire(WpRegisterScript::class),
    IWpRegisterScriptArgs::class => autowire(WpRegisterScriptArgs::class),
    IWpRegisterScriptArgsFactory::class => autowire(WpRegisterScriptArgsFactory::class),
    IWpRegisterScriptFactory::class => autowire(WpRegisterScriptFactory::class),

    IWpEnqueueScriptFunction::class => autowire(WpEnqueueScript::class),
    IWpEnqueueScriptArgs::class => autowire(WpEnqueueScriptArgs::class),
    IWpEnqueueScriptArgsFactory::class => autowire(WpEnqueueScriptArgsFactory::class),
    IWpEnqueueScriptFactory::class => autowire(WpEnqueueScriptFactory::class),

    IAssetDependency::class => autowire(AssetDependency::class),
    IAssetDependencyFactory::class => autowire(AssetDependencyFactory::class),
];
