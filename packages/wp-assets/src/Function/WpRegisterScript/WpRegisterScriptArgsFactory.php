<?php
declare(strict_types=1);

namespace LunaPress\Wp\Assets\Function\WpRegisterScript;

use LunaPress\Wp\AssetsContracts\Function\WpRegisterScript\IWpRegisterScriptArgs;
use LunaPress\Wp\AssetsContracts\Function\WpRegisterScript\IWpRegisterScriptArgsFactory;
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
