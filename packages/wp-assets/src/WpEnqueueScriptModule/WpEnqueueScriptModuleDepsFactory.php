<?php
declare(strict_types=1);

namespace LunaPress\Wp\Assets\WpEnqueueScriptModule;

use LunaPress\Wp\AssetsContracts\WpEnqueueScriptModule\IWpEnqueueScriptModuleDep;
use LunaPress\Wp\AssetsContracts\WpEnqueueScriptModule\IWpEnqueueScriptModuleDepFactory;
use Psr\Container\ContainerInterface;

defined('ABSPATH') || exit;

final readonly class WpEnqueueScriptModuleDepsFactory implements IWpEnqueueScriptModuleDepFactory
{
    public function __construct(
        private ContainerInterface $container,
    ) {
    }

    public function make(): IWpEnqueueScriptModuleDep
    {
        /** @var IWpEnqueueScriptModuleDep $deps */
        $deps = $this->container->get(IWpEnqueueScriptModuleDep::class);

        return $deps;
    }
}
