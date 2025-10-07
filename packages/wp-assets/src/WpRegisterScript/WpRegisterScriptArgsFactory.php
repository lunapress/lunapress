<?php
declare(strict_types=1);

namespace LunaPress\Wp\Assets\WpRegisterScript;

use LunaPress\Wp\AssetsContracts\WpRegisterScript\IWpRegisterScriptArgs;
use LunaPress\Wp\AssetsContracts\WpRegisterScript\IWpRegisterScriptArgsFactory;
use Psr\Container\ContainerInterface;

defined('ABSPATH') || exit;

final readonly class WpRegisterScriptArgsFactory implements IWpRegisterScriptArgsFactory
{
    public function __construct(
        private ContainerInterface $container,
    ) {
    }

    public function make(): IWpRegisterScriptArgs
    {
        /** @var IWpRegisterScriptArgs $args */
        $args = $this->container->get(IWpRegisterScriptArgs::class);

        return $args;
    }
}
