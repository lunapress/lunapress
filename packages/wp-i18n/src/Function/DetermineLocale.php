<?php

declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function;

final readonly class DetermineLocale
{
    public function __invoke(): string
    {
        return determine_locale();
    }
}
