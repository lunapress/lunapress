<?php

declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function;

final readonly class LoadPluginTextDomain
{
    public function __invoke(string $domain, string|false $pluginRelPath = false): bool
    {
        return load_plugin_textdomain($domain, false, $pluginRelPath);
    }
}
