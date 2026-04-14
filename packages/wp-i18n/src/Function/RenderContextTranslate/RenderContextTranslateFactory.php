<?php
declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function\RenderContextTranslate;

use LunaPress\Wp\I18nContracts\Function\RenderContextTranslate\IRenderContextTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\RenderContextTranslate\IRenderContextTranslateFunction;
use Psr\Container\ContainerInterface;

defined('ABSPATH') || exit;

final readonly class RenderContextTranslateFactory implements IRenderContextTranslateFactory
{
    public function __construct(
        private ContainerInterface $container,
    ) {
    }

    public function make(string $text, string $context): IRenderContextTranslateFunction
    {
        /** @var IRenderContextTranslateFunction $function */
        $function = $this->container->get(IRenderContextTranslateFunction::class);

        return $function
            ->text($text)
            ->context($context);
    }
}
