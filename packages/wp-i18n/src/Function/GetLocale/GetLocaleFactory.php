<?php
declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function\GetLocale;

use LunaPress\Wp\I18nContracts\Function\GetLocale\IGetLocaleFactory;
use LunaPress\Wp\I18nContracts\Function\GetLocale\IGetLocaleFunction;
use Psr\Container\ContainerInterface;

defined('ABSPATH') || exit;

final readonly class GetLocaleFactory implements IGetLocaleFactory
{
    public function __construct(
        private ContainerInterface $container,
    ) {
    }

    public function make(): IGetLocaleFunction
    {
        return $this->container->get(IGetLocaleFunction::class);
    }
}
