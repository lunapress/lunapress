<?php

declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function\NoopPluralTranslate;

use LunaPress\Wp\I18nContracts\Function\NoopPluralTranslate\INoopPluralTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\NoopPluralTranslate\INoopPluralTranslateFunction;
use Psr\Container\ContainerInterface;

final readonly class NoopPluralTranslateFactory implements INoopPluralTranslateFactory
{
    public function __construct(
        private ContainerInterface $container,
    ) {
    }

    public function make(string $single, string $plural): INoopPluralTranslateFunction
    {
        /** @var INoopPluralTranslateFunction $function */
        $function = $this->container->get(INoopPluralTranslateFunction::class);

        return $function
            ->single($single)
            ->plural($plural);
    }
}
