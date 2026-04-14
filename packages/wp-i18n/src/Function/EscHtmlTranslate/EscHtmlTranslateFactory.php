<?php
declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function\EscHtmlTranslate;

use LunaPress\Wp\I18nContracts\Function\EscHtmlTranslate\IEscHtmlTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\EscHtmlTranslate\IEscHtmlTranslateFunction;
use Psr\Container\ContainerInterface;

defined('ABSPATH') || exit;

final readonly class EscHtmlTranslateFactory implements IEscHtmlTranslateFactory
{
    public function __construct(
        private ContainerInterface $container,
    ) {
    }

    public function make(string $text): IEscHtmlTranslateFunction
    {
        /** @var IEscHtmlTranslateFunction $function */
        $function = $this->container->get(IEscHtmlTranslateFunction::class);

        return $function
            ->text($text);
    }
}
