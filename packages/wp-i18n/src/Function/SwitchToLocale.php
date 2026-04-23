<?php

declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function;

final readonly class SwitchToLocale
{
    public function __invoke(string $locale): bool
    {
        return switch_to_locale($locale);
    }
}
