<?php

declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function;

final readonly class LoadMuPluginTextDomain
{
    public function __invoke(string $domain, string $muPluginRelPath = ''): bool
    {
        return load_muplugin_textdomain($domain, $muPluginRelPath);
    }
}
