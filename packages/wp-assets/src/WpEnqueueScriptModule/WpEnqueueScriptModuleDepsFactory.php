<?php
declare(strict_types=1);

namespace LunaPress\Wp\Assets\WpEnqueueScriptModule;

use LunaPress\Wp\AssetsContracts\WpEnqueueScriptModule\IWpEnqueueScriptModuleDeps;
use LunaPress\Wp\AssetsContracts\WpEnqueueScriptModule\IWpEnqueueScriptModuleDepsFactory;
use Psr\Container\ContainerInterface;

defined('ABSPATH') || exit;

final readonly class WpEnqueueScriptModuleDepsFactory implements IWpEnqueueScriptModuleDepsFactory
{
    public function __construct(
        private ContainerInterface $container,
    ) {
    }

    public function make(): IWpEnqueueScriptModuleDeps
    {
        /** @var IWpEnqueueScriptModuleDeps $deps */
        $deps = $this->container->get(IWpEnqueueScriptModuleDeps::class);

        return $deps;
    }
}
