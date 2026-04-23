<?php

declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function;

final readonly class LoadThemeTextDomain
{
    public function __invoke(string $domain, string|false $path = false): bool
    {
        return load_theme_textdomain($domain, $path);
    }
}
