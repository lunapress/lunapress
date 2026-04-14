<?php
declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function\SwitchToLocale;

use LunaPress\Wp\I18nContracts\Function\SwitchToLocale\ISwitchToLocaleFactory;
use LunaPress\Wp\I18nContracts\Function\SwitchToLocale\ISwitchToLocaleFunction;
use Psr\Container\ContainerInterface;

defined('ABSPATH') || exit;

final readonly class SwitchToLocaleFactory implements ISwitchToLocaleFactory
{
    public function __construct(
        private ContainerInterface $container,
    ) {
    }

    public function make(string $locale): ISwitchToLocaleFunction
    {
        /** @var ISwitchToLocaleFunction $function */
        $function = $this->container->get(ISwitchToLocaleFunction::class);

        return $function->locale($locale);
    }
}
