<?php
declare(strict_types=1);

namespace LunaPress\Wp\Core\WpDie;

use LunaPress\Wp\CoreContracts\WpDie\IWpDieFunction;
use LunaPress\Wp\CoreContracts\WpError;
use LunaPress\Wp\CoreContracts\WpDie\IWpDieBuilder;
use Psr\Container\ContainerInterface;

defined('ABSPATH') || exit;

final readonly class WpDieBuilder implements IWpDieBuilder
{
    public function __construct(
        private ContainerInterface $container,
    ) {
    }

    public function make(WpError|string $message): IWpDieFunction
    {
        /** @var IWpDieFunction $function */
        $function = $this->container->get(IWpDieFunction::class);

        return $function
            ->message($message);
    }
}
