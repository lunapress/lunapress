<?php

declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function\DetermineLocale;

use LunaPress\Wp\I18nContracts\Function\DetermineLocale\IDetermineLocaleFactory;
use LunaPress\Wp\I18nContracts\Function\DetermineLocale\IDetermineLocaleFunction;
use Psr\Container\ContainerInterface;

final readonly class DetermineLocaleFactory implements IDetermineLocaleFactory
{
    public function __construct(
        private ContainerInterface $container,
    ) {
    }

    public function make(): IDetermineLocaleFunction
    {
        return $this->container->get(IDetermineLocaleFunction::class);
    }
}
