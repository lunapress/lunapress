<?php
declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function\RenderTranslate;

use LunaPress\Wp\I18nContracts\Function\RenderTranslate\IRenderTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\RenderTranslate\IRenderTranslateFunction;
use Psr\Container\ContainerInterface;

defined('ABSPATH') || exit;

final readonly class RenderTranslateFactory implements IRenderTranslateFactory
{
    public function __construct(
        private ContainerInterface $container,
    ) {
    }

    public function make(string $text): IRenderTranslateFunction
    {
        /** @var IRenderTranslateFunction $function */
        $function = $this->container->get(IRenderTranslateFunction::class);

        return $function
            ->text($text);
    }
}
