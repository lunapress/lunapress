<?php

declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function;

final readonly class IsTextDomainLoaded
{
    public function __invoke(string $domain): bool
    {
        return is_textdomain_loaded($domain);
    }
}
