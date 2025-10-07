<?php
declare(strict_types=1);

namespace LunaPress\Wp\Core\WpKses;

use LunaPress\Wp\CoreContracts\WpKses\IWpKsesBuilder;
use LunaPress\Wp\CoreContracts\WpKses\IWpKsesFunction;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

defined('ABSPATH') || exit;

final readonly class WpKsesBuilder implements IWpKsesBuilder
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
