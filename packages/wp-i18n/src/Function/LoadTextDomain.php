<?php

declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function;

final readonly class LoadTextDomain
{
    public function __invoke(string $domain, string $moFile, ?string $locale = null): bool
    {
        return load_textdomain($domain, $moFile, $locale);
    }
}
