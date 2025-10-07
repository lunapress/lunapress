<?php
declare(strict_types=1);

namespace LunaPress\Wp\Assets\WpRegisterScript;

use LunaPress\Wp\AssetsContracts\WpRegisterScript\IWpRegisterScriptFunction;
use LunaPress\Wp\AssetsContracts\WpRegisterScript\IWpRegisterScriptBuilder;
use Psr\Container\ContainerInterface;

defined('ABSPATH') || exit;

final readonly class WpRegisterScriptBuilder implements IWpRegisterScriptBuilder
{
    public function __construct(
        private ContainerInterface $container,
    ) {
    }

    public function make(string $handle, false|string $src): IWpRegisterScriptFunction
    {
        /** @var IWpRegisterScriptFunction $function */
        $function = $this->container->get(IWpRegisterScriptFunction::class);

        return $function
            ->handle($handle)
            ->src($src);
    }
}
