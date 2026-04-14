<?php
declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function\TranslateNoopedPlural;

use LunaPress\Wp\I18nContracts\Entity\INoopedPlural;
use LunaPress\Wp\I18nContracts\Function\TranslateNoopedPlural\ITranslateNoopedPluralFactory;
use LunaPress\Wp\I18nContracts\Function\TranslateNoopedPlural\ITranslateNoopedPluralFunction;
use Psr\Container\ContainerInterface;

defined('ABSPATH') || exit;

final readonly class TranslateNoopedPluralFactory implements ITranslateNoopedPluralFactory
{
    public function __construct(
        private ContainerInterface $container,
    ) {
    }

    public function make(INoopedPlural $noopedPlural, int $count): ITranslateNoopedPluralFunction
    {
        /** @var ITranslateNoopedPluralFunction $function */
        $function = $this->container->get(ITranslateNoopedPluralFunction::class);

        return $function
            ->noopedPlural($noopedPlural)
            ->count($count);
    }
}
