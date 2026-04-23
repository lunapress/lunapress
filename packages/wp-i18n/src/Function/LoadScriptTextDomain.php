<?php

declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function;

use LunaPress\Wp\I18nContracts\I18n;

final readonly class LoadScriptTextDomain
{
    public function __invoke(string $handle, string $domain = I18n::DEFAULT_DOMAIN, string $path = ''): string|false
    {
        return load_script_textdomain($handle, $domain, $path);
    }
}
