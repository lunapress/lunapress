<?php

declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function\EscAttrContextTranslate;

use LunaPress\Wp\I18nContracts\Function\EscAttrContextTranslate\IEscAttrContextTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\EscAttrContextTranslate\IEscAttrContextTranslateFunction;
use Psr\Container\ContainerInterface;

final readonly class EscAttrContextTranslateFactory implements IEscAttrContextTranslateFactory
{
    public function __construct(
        private ContainerInterface $container,
    ) {
    }

    public function make(string $text, string $context): IEscAttrContextTranslateFunction
    {
        /** @var IEscAttrContextTranslateFunction $function */
        $function = $this->container->get(IEscAttrContextTranslateFunction::class);

        return $function
            ->text($text)
            ->context($context);
    }
}
