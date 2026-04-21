<?php

declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function\TranslateNoopedPlural;

use LunaPress\Wp\I18n\Trait\HasDomain;
use LunaPress\Wp\I18nContracts\Entity\INoopedPlural;
use LunaPress\Wp\I18nContracts\Function\TranslateNoopedPlural\ITranslateNoopedPluralFunction;

final class TranslateNoopedPlural implements ITranslateNoopedPluralFunction
{
    use HasDomain;

    private INoopedPlural $noopedPlural;
    private int $count;

    public function noopedPlural(INoopedPlural $noopedPlural): static
    {
        $this->noopedPlural = $noopedPlural;
        return $this;
    }

    public function count(int $count): static
    {
        $this->count = $count;
        return $this;
    }

    public function getNoopedPlural(): INoopedPlural
    {
        return $this->noopedPlural;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function rawArgs(): array
    {
        $noop = $this->getNoopedPlural();

        return [
            [
                'singular' => $noop->getSingle(),
                'plural'   => $noop->getPlural(),
                'context'  => $noop->getContext(),
                'domain'   => $noop->getDomain(),
            ],
            $this->getCount(),
            $this->getDomain(),
        ];
    }

    public function executeWithArgs(array $args): string
    {
        return translate_nooped_plural(...$args);
    }
}
