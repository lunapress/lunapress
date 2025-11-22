<?php
declare(strict_types=1);

namespace LunaPress\Wp\Assets\Function\WpEnqueueScript;

use LunaPress\Wp\AssetsContracts\Function\WpEnqueueScript\IWpEnqueueScriptFactory;
use LunaPress\Wp\AssetsContracts\Function\WpEnqueueScript\IWpEnqueueScriptFunction;
use Psr\Container\ContainerInterface;

defined('ABSPATH') || exit;

class WpEnqueueScriptFactory implements IWpEnqueueScriptFactory
{
    public function __construct(
        private ContainerInterface $container,
    ) {
    }
    public function make(string $handle): IWpEnqueueScriptFunction
    {
        /** @var IWpEnqueueScriptFunction $function */
        $function = $this->container->get(IWpEnqueueScriptFunction::class);

        return $function
            ->handle($handle);
    }
}
