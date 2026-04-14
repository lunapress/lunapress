<?php
declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function\LoadMuPluginTextDomain;

use LunaPress\Wp\I18n\Trait\HasDomain;
use LunaPress\Wp\I18nContracts\Function\LoadMuPluginTextDomain\ILoadMuPluginTextDomainFunction;

defined('ABSPATH') || exit;

final class LoadMuPluginTextDomain implements ILoadMuPluginTextDomainFunction
{
    use HasDomain;

    private string $muPluginRelPath = '';

    public function muPluginRelPath(string $muPluginRelPath): static
    {
        $this->muPluginRelPath = $muPluginRelPath;
        return $this;
    }

    public function getMuPluginRelPath(): string
    {
        return $this->muPluginRelPath;
    }

    public function rawArgs(): array
    {
        return [
            $this->getDomain(),
            $this->getMuPluginRelPath()
        ];
    }

    public function executeWithArgs(array $args): bool
    {
        return load_muplugin_textdomain(...$args);
    }
}
