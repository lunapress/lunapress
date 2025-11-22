<?php
declare(strict_types=1);

namespace LunaPress\Wp\Assets\Function\WpEnqueueScriptModule;

use LunaPress\Wp\AssetsContracts\Function\WpEnqueueScriptModule\IWpEnqueueScriptModuleFunction;
use LunaPress\Wp\AssetsContracts\Function\WpEnqueueScriptModule\IWpEnqueueScriptModuleFactory;
use Psr\Container\ContainerInterface;

defined('ABSPATH') || exit;

final readonly class WpEnqueueScriptModuleFactory implements IWpEnqueueScriptModuleFactory
{
    public function __construct(
        private ContainerInterface $container,
    ) {
    }

    public function make(string $id): IWpEnqueueScriptModuleFunction
    {
        /** @var IWpEnqueueScriptModuleFunction $function */
        $function = $this->container->get(IWpEnqueueScriptModuleFunction::class);

        return $function
            ->id($id);
    }
}
