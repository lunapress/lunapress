<?php

declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function\LoadChildThemeTextDomain;

use LunaPress\Wp\I18n\Trait\HasDomain;
use LunaPress\Wp\I18nContracts\Function\LoadChildThemeTextDomain\ILoadChildThemeTextDomainFunction;

final class LoadChildThemeTextDomain implements ILoadChildThemeTextDomainFunction
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
            $this->getPath(),
        ];
    }

    public function executeWithArgs(array $args): bool
    {
        return load_child_theme_textdomain(...$args);
    }
}
