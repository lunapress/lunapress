<?php
declare(strict_types=1);

namespace LunaPress\Wp\Assets\WpEnqueueScriptModule;

use LunaPress\Wp\AssetsContracts\WpEnqueueScriptModule\IWpEnqueueScriptModuleFunction;
use LunaPress\Wp\AssetsContracts\WpEnqueueScriptModule\IWpEnqueueScriptModuleBuilder;
use Psr\Container\ContainerInterface;

defined('ABSPATH') || exit;

final readonly class WpEnqueueScriptModuleBuilder implements IWpEnqueueScriptModuleBuilder
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
