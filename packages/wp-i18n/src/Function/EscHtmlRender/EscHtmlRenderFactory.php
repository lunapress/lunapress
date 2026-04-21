<?php

declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function\EscHtmlRender;

use LunaPress\Wp\I18nContracts\Function\EscHtmlRender\IEscHtmlRenderFactory;
use LunaPress\Wp\I18nContracts\Function\EscHtmlRender\IEscHtmlRenderFunction;
use Psr\Container\ContainerInterface;

final readonly class EscHtmlRenderFactory implements IEscHtmlRenderFactory
{
    public function __construct(
        private ContainerInterface $container,
    ) {
    }

    public function make(string $text): IEscHtmlRenderFunction
    {
        /** @var IEscHtmlRenderFunction $function */
        $function = $this->container->get(IEscHtmlRenderFunction::class);

        return $function
            ->text($text);
    }
}
