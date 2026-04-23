<?php

declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function;

final readonly class UnloadTextDomain
{
    public function __invoke(string $domain, bool $reloadable = false): bool
    {
        return unload_textdomain($domain, $reloadable);
    }
}
