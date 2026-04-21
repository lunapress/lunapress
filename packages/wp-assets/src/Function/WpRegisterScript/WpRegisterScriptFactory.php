<?php

declare(strict_types=1);

namespace LunaPress\Wp\Assets\Function\WpRegisterScript;

use LunaPress\Wp\AssetsContracts\Function\WpRegisterScript\IWpRegisterScriptFactory;
use LunaPress\Wp\AssetsContracts\Function\WpRegisterScript\IWpRegisterScriptFunction;
use Psr\Container\ContainerInterface;

final readonly class WpRegisterScriptFactory implements IWpRegisterScriptFactory
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
