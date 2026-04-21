<?php

declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function\ContextNoopPluralTranslate;

use LunaPress\Wp\I18nContracts\Function\ContextNoopPluralTranslate\IContextNoopPluralTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\ContextNoopPluralTranslate\IContextNoopPluralTranslateFunction;
use Psr\Container\ContainerInterface;

final readonly class ContextNoopPluralTranslateFactory implements IContextNoopPluralTranslateFactory
{
    public function __construct(
        private ContainerInterface $container,
    ) {
    }

    public function make(string $single, string $plural, string $context): IContextNoopPluralTranslateFunction
    {
        /** @var IContextNoopPluralTranslateFunction $function */
        $function = $this->container->get(IContextNoopPluralTranslateFunction::class);

        return $function
            ->single($single)
            ->plural($plural)
            ->context($context);
    }
}
