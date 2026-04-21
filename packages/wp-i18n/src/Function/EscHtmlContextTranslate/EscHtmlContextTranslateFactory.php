<?php

declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function\EscHtmlContextTranslate;

use LunaPress\Wp\I18nContracts\Function\EscHtmlContextTranslate\IEscHtmlContextTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\EscHtmlContextTranslate\IEscHtmlContextTranslateFunction;
use Psr\Container\ContainerInterface;

final readonly class EscHtmlContextTranslateFactory implements IEscHtmlContextTranslateFactory
{
    public function __construct(
        private ContainerInterface $container,
    ) {
    }

    public function make(string $text, string $context): IEscHtmlContextTranslateFunction
    {
        /** @var IEscHtmlContextTranslateFunction $function */
        $function = $this->container->get(IEscHtmlContextTranslateFunction::class);

        return $function
            ->text($text)
            ->context($context);
    }
}
