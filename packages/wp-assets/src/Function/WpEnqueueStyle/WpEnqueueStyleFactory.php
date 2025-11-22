<?php
declare(strict_types=1);

namespace LunaPress\Wp\Assets\Function\WpEnqueueStyle;

use LunaPress\Wp\AssetsContracts\Function\WpEnqueueStyle\IWpEnqueueStyleFunction;
use LunaPress\Wp\AssetsContracts\Function\WpEnqueueStyle\IWpEnqueueStyleFactory;
use Psr\Container\ContainerInterface;

defined('ABSPATH') || exit;

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
