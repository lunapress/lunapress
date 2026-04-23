<?php

declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function;

final readonly class LoadChildThemeTextDomain
{
    public function __invoke(string $domain, string|false $path = false): bool
    {
        return load_child_theme_textdomain($domain, $path);
    }
}
