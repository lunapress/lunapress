<?php
declare(strict_types=1);

namespace LunaPress\Wp\Assets\WpEnqueueScript;

use LunaPress\Wp\AssetsContracts\WpEnqueueScript\Enum\WpEnqueueScriptStrategy;
use LunaPress\Wp\AssetsContracts\WpEnqueueScript\IWpEnqueueScriptArgs;
use LunaPress\Wp\AssetsContracts\WpEnqueueScript\IWpEnqueueScriptArgsFactory;
use Psr\Container\ContainerInterface;

defined('ABSPATH') || exit;

final readonly class WpEnqueueScriptArgsFactory implements IWpEnqueueScriptArgsFactory
{
    public function __construct(
        private ContainerInterface $container,
    ) {
    }

    public function make(WpEnqueueScriptStrategy $strategy, bool $inFooter): IWpEnqueueScriptArgs
    {
        /** @var IWpEnqueueScriptArgs $args */
        $args = $this->container->get(IWpEnqueueScriptArgs::class);

        return $args
            ->strategy($strategy)
            ->inFooter($inFooter);
    }
}
