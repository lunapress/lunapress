<?php

declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function\GetUserLocale;

use LunaPress\Wp\I18nContracts\Function\GetUserLocale\IGetUserLocaleFactory;
use LunaPress\Wp\I18nContracts\Function\GetUserLocale\IGetUserLocaleFunction;
use Psr\Container\ContainerInterface;

final readonly class GetUserLocaleFactory implements IGetUserLocaleFactory
{
    public function __construct(
        private ContainerInterface $container,
    ) {
    }

    public function make(): IGetUserLocaleFunction
    {
        return $this->container->get(IGetUserLocaleFunction::class);
    }
}
