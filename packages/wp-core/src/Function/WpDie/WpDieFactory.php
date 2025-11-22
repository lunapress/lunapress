<?php
declare(strict_types=1);

namespace LunaPress\Wp\Core\Function\WpDie;

use LunaPress\Wp\CoreContracts\Entity\WpError\IWpError;
use LunaPress\Wp\CoreContracts\Function\WpDie\IWpDieFunction;
use LunaPress\Wp\CoreContracts\Function\WpDie\IWpDieFactory;
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
