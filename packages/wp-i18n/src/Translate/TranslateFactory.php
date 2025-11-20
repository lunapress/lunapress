<?php
declare(strict_types=1);

namespace LunaPress\Wp\I18n\Translate;

use LunaPress\Wp\I18nContracts\Translate\ITranslateFactory;
use LunaPress\Wp\I18nContracts\Translate\ITranslateFunction;
use Psr\Container\ContainerInterface;

defined('ABSPATH') || exit;

final readonly class TranslateFactory implements ITranslateFactory
{
    public function __construct(
        private ContainerInterface $container,
    ) {
    }

    public function make(string $text): ITranslateFunction
    {
        /** @var ITranslateFunction $function */
        $function = $this->container->get(ITranslateFunction::class);

        return $function
            ->text($text);
    }
}
