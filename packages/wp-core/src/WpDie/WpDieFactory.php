<?php
declare(strict_types=1);

namespace LunaPress\Wp\Core\WpDie;

use LunaPress\Wp\CoreContracts\WpDie\IWpDieFunction;
use LunaPress\Wp\CoreContracts\IWpError;
use LunaPress\Wp\CoreContracts\WpDie\IWpDieFactory;
use Psr\Container\ContainerInterface;

defined('ABSPATH') || exit;

final readonly class WpDieFactory implements IWpDieFactory
{
    public function __construct(
        private ContainerInterface $container,
    ) {
    }

    public function make(IWpError|string $message): IWpDieFunction
    {
        /** @var IWpDieFunction $function */
        $function = $this->container->get(IWpDieFunction::class);

        return $function
            ->message($message);
    }
}
