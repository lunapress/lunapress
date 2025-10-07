<?php
declare(strict_types=1);

namespace LunaPress\Wp\Assets\WpEnqueueStyle;

use LunaPress\Wp\AssetsContracts\WpEnqueueStyle\IWpEnqueueStyleFunction;
use LunaPress\Wp\AssetsContracts\WpEnqueueStyle\IWpEnqueueStyleBuilder;
use Psr\Container\ContainerInterface;

defined('ABSPATH') || exit;

final readonly class WpEnqueueStyleBuilder implements IWpEnqueueStyleBuilder
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
