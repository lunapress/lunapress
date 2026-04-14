<?php
declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function\LoadThemeTextDomain;

use LunaPress\Wp\I18n\Trait\HasDomain;
use LunaPress\Wp\I18nContracts\Function\LoadThemeTextDomain\ILoadThemeTextDomainFunction;

defined('ABSPATH') || exit;

final class LoadThemeTextDomain implements ILoadThemeTextDomainFunction
{
    use HasDomain;

    private string|false $path = false;

    public function path(string|false $path): static
    {
        $this->path = $path;
        return $this;
    }

    public function getPath(): string|false
    {
        return $this->path;
    }

    public function rawArgs(): array
    {
        return [
            $this->getDomain(),
            $this->getPath()
        ];
    }

    public function executeWithArgs(array $args): bool
    {
        return load_theme_textdomain(...$args);
    }
}
