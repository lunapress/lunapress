<?php
declare(strict_types=1);

namespace LunaPress\Wp\Core\WpDie;

use LunaPress\Wp\CoreContracts\WpDie\IWpDieArgs;
use LunaPress\Wp\CoreContracts\WpDie\IWpDieArgsFactory;
use Psr\Container\ContainerInterface;

defined('ABSPATH') || exit;

final readonly class WpDieArgsFactory implements IWpDieArgsFactory
{
    public function __construct(
        private ContainerInterface $container,
    ) {
    }

    public function make(): IWpDieArgs
    {
        /** @var IWpDieArgs $args */
        $args = $this->container->get(IWpDieArgs::class);

        return $args;
    }
}
