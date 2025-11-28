<?php
declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function\PluralTranslate;

use LunaPress\Wp\I18nContracts\Function\PluralTranslate\IPluralTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\PluralTranslate\IPluralTranslateFunction;
use Psr\Container\ContainerInterface;

defined('ABSPATH') || exit;

final readonly class PluralTranslateFactory implements IPluralTranslateFactory
{
    public function __construct(
        private ContainerInterface $container,
    ) {
    }

    public function make(string $single, string $plural, int $number): IPluralTranslateFunction
    {
        /** @var IPluralTranslateFunction $function */
        $function = $this->container->get(IPluralTranslateFunction::class);

        return $function
            ->single($single)
            ->plural($plural)
            ->number($number);
    }
}
