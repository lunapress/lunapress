<?php
declare(strict_types=1);

namespace LunaPress\Wp\Core\Function\WpDie;

use LunaPress\Wp\CoreContracts\Function\WpDie\IWpDieArgs;
use LunaPress\Wp\CoreContracts\Function\WpDie\IWpDieArgsFactory;
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
