<?php
declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function\EscAttrTranslate;

use LunaPress\Wp\I18nContracts\Function\EscAttrTranslate\IEscAttrTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\EscAttrTranslate\IEscAttrTranslateFunction;
use Psr\Container\ContainerInterface;

defined('ABSPATH') || exit;

final readonly class EscAttrTranslateFactory implements IEscAttrTranslateFactory
{
    public function __construct(
        private ContainerInterface $container,
    ) {
    }

    public function make(string $text): IEscAttrTranslateFunction
    {
        /** @var IEscAttrTranslateFunction $function */
        $function = $this->container->get(IEscAttrTranslateFunction::class);

        return $function
            ->text($text);
    }
}
