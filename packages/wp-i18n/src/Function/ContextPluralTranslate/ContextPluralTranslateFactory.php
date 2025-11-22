<?php
declare(strict_types=1);

namespace LunaPress\Wp\I18n\Functions\ContextPluralTranslate;

use LunaPress\Wp\I18nContracts\Function\ContextPluralTranslate\IContextPluralTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\ContextPluralTranslate\IContextPluralTranslateFunction;
use Psr\Container\ContainerInterface;

defined('ABSPATH') || exit;

final readonly class ContextPluralTranslateFactory implements IContextPluralTranslateFactory
{
    public function __construct(
        private ContainerInterface $container,
    ) {
    }

    public function make(string $single, string $plural, int $number, string $context): IContextPluralTranslateFunction
    {
        /** @var IContextPluralTranslateFunction $function */
        $function = $this->container->get(IContextPluralTranslateFunction::class);

        return $function
            ->single($single)
            ->plural($plural)
            ->number($number)
            ->context($context);
    }
}
