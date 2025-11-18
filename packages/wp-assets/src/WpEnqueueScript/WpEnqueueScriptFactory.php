<?php
declare(strict_types=1);

namespace LunaPress\Wp\Assets\WpEnqueueScript;

use LunaPress\Wp\AssetsContracts\WpEnqueueScript\IWpEnqueueScriptFactory;
use LunaPress\Wp\AssetsContracts\WpEnqueueScript\IWpEnqueueScriptFunction;
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
