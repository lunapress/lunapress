<?php
declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function\NumberFormatI18n;

use LunaPress\Wp\I18nContracts\Function\NumberFormatI18n\INumberFormatI18nFactory;
use LunaPress\Wp\I18nContracts\Function\NumberFormatI18n\INumberFormatI18nFunction;
use Psr\Container\ContainerInterface;

defined('ABSPATH') || exit;

final readonly class NumberFormatI18nFactory implements INumberFormatI18nFactory
{
    public function __construct(
        private ContainerInterface $container,
    ) {
    }

    public function make(float $number): INumberFormatI18nFunction
    {
        /** @var INumberFormatI18nFunction $function */
        $function = $this->container->get(INumberFormatI18nFunction::class);

        return $function->number($number);
    }
}
