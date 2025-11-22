<?php
declare(strict_types=1);

namespace LunaPress\Wp\I18n\Functions\ContextTranslate;

use LunaPress\Wp\I18nContracts\Function\ContextTranslate\IContextTranslateFactory;
use LunaPress\Wp\I18nContracts\Function\ContextTranslate\IContextTranslateFunction;
use Psr\Container\ContainerInterface;

defined('ABSPATH') || exit;

final readonly class ContextTranslateFactory implements IContextTranslateFactory
{
    public function __construct(
        private ContainerInterface $container,
    ) {
    }

    public function make(string $text, string $context): IContextTranslateFunction
    {
        /** @var IContextTranslateFunction $function */
        $function = $this->container->get(IContextTranslateFunction::class);

        return $function
            ->text($text)
            ->context($context);
    }
}
