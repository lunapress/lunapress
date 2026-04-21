<?php

declare(strict_types=1);

namespace LunaPress\Wp\Assets\Function\WpEnqueueStyle;

use LunaPress\Wp\AssetsContracts\Function\WpEnqueueStyle\IWpEnqueueStyleFactory;
use LunaPress\Wp\AssetsContracts\Function\WpEnqueueStyle\IWpEnqueueStyleFunction;
use Psr\Container\ContainerInterface;

final readonly class WpEnqueueStyleFactory implements IWpEnqueueStyleFactory
{
    public function __construct(
        private ContainerInterface $container,
    ) {
    }

    public function make(string $handle): IWpEnqueueStyleFunction
    {
        /** @var IWpEnqueueStyleFunction $function */
        $function = $this->container->get(IWpEnqueueStyleFunction::class);

        return $function
            ->handle($handle);
    }
}
