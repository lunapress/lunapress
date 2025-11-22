<?php
declare(strict_types=1);

namespace LunaPress\Wp\Core\Function\WpKses;

use LunaPress\Wp\CoreContracts\Function\WpKses\IWpKsesFactory;
use LunaPress\Wp\CoreContracts\Function\WpKses\IWpKsesFunction;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

defined('ABSPATH') || exit;

final readonly class WpKsesFactory implements IWpKsesFactory
{
    public function __construct(
        private ContainerInterface $container,
    ) {
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function make(string $content, array|string $allowedHtml): IWpKsesFunction
    {
        /** @var IWpKsesFunction $function */
        $function = $this->container->get(IWpKsesFunction::class);

        return $function
            ->content($content)
            ->allowedHtml($allowedHtml);
    }
}
