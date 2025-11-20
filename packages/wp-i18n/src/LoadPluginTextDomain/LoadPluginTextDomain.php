<?php
declare(strict_types=1);

namespace LunaPress\Wp\I18n\LoadPluginTextDomain;

use LunaPress\Wp\I18nContracts\LoadPluginTextDomain\ILoadPluginTextDomainFunction;

defined('ABSPATH') || exit;

final class LoadPluginTextDomain implements ILoadPluginTextDomainFunction
{
    private string $domain;
    private false|string $pluginRelPath = false;

    public function rawArgs(): array
    {
        return [
            $this->getDomain(),
            false,
            $this->getPluginRelPath()
        ];
    }

    public function executeWithArgs(array $args): bool
    {
        return load_plugin_textdomain(...$args);
    }

    public function domain(string $domain): static
    {
        $this->domain = $domain;
        return $this;
    }

    public function pluginRelPath(false|string $pluginRelPath): static
    {
        $this->pluginRelPath = $pluginRelPath;
        return $this;
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function getPluginRelPath(): string|false
    {
        return $this->pluginRelPath;
    }
}
