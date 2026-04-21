<?php

declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function\EscAttrRender;

use LunaPress\Wp\I18nContracts\Function\EscAttrRender\IEscAttrRenderFactory;
use LunaPress\Wp\I18nContracts\Function\EscAttrRender\IEscAttrRenderFunction;
use Psr\Container\ContainerInterface;

final readonly class EscAttrRenderFactory implements IEscAttrRenderFactory
{
    public function __construct(
        private ContainerInterface $container,
    ) {
    }

    public function make(string $text): IEscAttrRenderFunction
    {
        /** @var IEscAttrRenderFunction $function */
        $function = $this->container->get(IEscAttrRenderFunction::class);

        return $function
            ->text($text);
    }
}
