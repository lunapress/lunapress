<?php
declare(strict_types=1);

namespace LunaPress\Wp\I18n\ContextPluralTranslate;

use LunaPress\Wp\I18nContracts\ContextPluralTranslate\IContextPluralTranslateFactory;
use LunaPress\Wp\I18nContracts\ContextPluralTranslate\IContextPluralTranslateFunction;
use Symfony\Component\DependencyInjection\ContainerInterface;

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
