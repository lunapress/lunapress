<?php
declare(strict_types=1);

use LunaPress\Wp\Assets\Entity\AssetDependency;
use LunaPress\Wp\Assets\Factory\AssetDependencyFactory;
use LunaPress\Wp\Assets\Function\WpEnqueueScript\WpEnqueueScript;
use LunaPress\Wp\Assets\Function\WpEnqueueScript\WpEnqueueScriptArgs;
use LunaPress\Wp\Assets\Function\WpEnqueueScript\WpEnqueueScriptArgsFactory;
use LunaPress\Wp\Assets\Function\WpEnqueueScript\WpEnqueueScriptFactory;
use LunaPress\Wp\Assets\Function\WpEnqueueScriptModule\WpEnqueueScriptModule;
use LunaPress\Wp\Assets\Function\WpEnqueueScriptModule\WpEnqueueScriptModuleFactory;
use LunaPress\Wp\Assets\Function\WpEnqueueScriptModule\WpEnqueueScriptModuleDep;
use LunaPress\Wp\Assets\Function\WpEnqueueScriptModule\WpEnqueueScriptModuleDepsFactory;
use LunaPress\Wp\Assets\Function\WpEnqueueStyle\WpEnqueueStyle;
use LunaPress\Wp\Assets\Function\WpEnqueueStyle\WpEnqueueStyleFactory;
use LunaPress\Wp\Assets\Function\WpRegisterScript\WpRegisterScript;
use LunaPress\Wp\Assets\Function\WpRegisterScript\WpRegisterScriptArgs;
use LunaPress\Wp\Assets\Function\WpRegisterScript\WpRegisterScriptArgsFactory;
use LunaPress\Wp\Assets\Function\WpRegisterScript\WpRegisterScriptFactory;
use LunaPress\Wp\AssetsContracts\Entity\IAssetDependency;
use LunaPress\Wp\AssetsContracts\Factory\IAssetDependencyFactory;
use LunaPress\Wp\AssetsContracts\Function\WpEnqueueScript\IWpEnqueueScriptArgs;
use LunaPress\Wp\AssetsContracts\Function\WpEnqueueScript\IWpEnqueueScriptArgsFactory;
use LunaPress\Wp\AssetsContracts\Function\WpEnqueueScript\IWpEnqueueScriptFactory;
use LunaPress\Wp\AssetsContracts\Function\WpEnqueueScript\IWpEnqueueScriptFunction;
use LunaPress\Wp\AssetsContracts\Function\WpEnqueueScriptModule\IWpEnqueueScriptModuleDep;
use LunaPress\Wp\AssetsContracts\Function\WpEnqueueScriptModule\IWpEnqueueScriptModuleDepFactory;
use LunaPress\Wp\AssetsContracts\Function\WpEnqueueScriptModule\IWpEnqueueScriptModuleFactory;
use LunaPress\Wp\AssetsContracts\Function\WpEnqueueScriptModule\IWpEnqueueScriptModuleFunction;
use LunaPress\Wp\AssetsContracts\Function\WpEnqueueStyle\IWpEnqueueStyleFactory;
use LunaPress\Wp\AssetsContracts\Function\WpEnqueueStyle\IWpEnqueueStyleFunction;
use LunaPress\Wp\AssetsContracts\Function\WpRegisterScript\IWpRegisterScriptArgs;
use LunaPress\Wp\AssetsContracts\Function\WpRegisterScript\IWpRegisterScriptArgsFactory;
use LunaPress\Wp\AssetsContracts\Function\WpRegisterScript\IWpRegisterScriptFactory;
use LunaPress\Wp\AssetsContracts\Function\WpRegisterScript\IWpRegisterScriptFunction;
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
