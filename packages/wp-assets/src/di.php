<?php
declare(strict_types=1);

use LunaPress\Wp\Assets\AssetDependency;
use LunaPress\Wp\Assets\WpEnqueueScriptModule\WpEnqueueScriptModule;
use LunaPress\Wp\Assets\WpEnqueueScriptModule\WpEnqueueScriptModuleFactory;
use LunaPress\Wp\Assets\WpEnqueueScriptModule\WpEnqueueScriptModuleDeps;
use LunaPress\Wp\Assets\WpEnqueueScriptModule\WpEnqueueScriptModuleDepsFactory;
use LunaPress\Wp\Assets\WpEnqueueStyle\WpEnqueueStyle;
use LunaPress\Wp\Assets\WpEnqueueStyle\WpEnqueueStyleFactory;
use LunaPress\Wp\Assets\WpRegisterScript\WpRegisterScript;
use LunaPress\Wp\Assets\WpRegisterScript\WpRegisterScriptArgs;
use LunaPress\Wp\Assets\WpRegisterScript\WpRegisterScriptArgsFactory;
use LunaPress\Wp\Assets\WpRegisterScript\WpRegisterScriptFactory;
use LunaPress\Wp\AssetsContracts\IAssetDependency;
use LunaPress\Wp\AssetsContracts\WpEnqueueScriptModule\IWpEnqueueScriptModuleFactory;
use LunaPress\Wp\AssetsContracts\WpEnqueueScriptModule\IWpEnqueueScriptModuleDeps;
use LunaPress\Wp\AssetsContracts\WpEnqueueScriptModule\IWpEnqueueScriptModuleDepsFactory;
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
    IWpEnqueueScriptModuleDeps::class => autowire(WpEnqueueScriptModuleDeps::class),
    IWpEnqueueScriptModuleDepsFactory::class => autowire(WpEnqueueScriptModuleDepsFactory::class),

    IWpEnqueueStyleFunction::class => autowire(WpEnqueueStyle::class),
    IWpEnqueueStyleFactory::class => autowire(WpEnqueueStyleFactory::class),

    IWpRegisterScriptFunction::class => autowire(WpRegisterScript::class),
    IWpRegisterScriptArgs::class => autowire(WpRegisterScriptArgs::class),
    IWpRegisterScriptArgsFactory::class => autowire(WpRegisterScriptArgsFactory::class),
    IWpRegisterScriptFactory::class => autowire(WpRegisterScriptFactory::class),

    IAssetDependency::class => autowire(AssetDependency::class),
];
