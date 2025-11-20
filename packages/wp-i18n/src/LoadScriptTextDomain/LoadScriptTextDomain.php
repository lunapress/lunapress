<?php
declare(strict_types=1);

namespace LunaPress\Wp\I18n\LoadScriptTextDomain;

use LunaPress\Wp\I18n\Support\Concerns\HasDomain;
use LunaPress\Wp\I18nContracts\LoadScriptTextDomain\ILoadScriptTextDomainFunction;

defined('ABSPATH') || exit;

final class LoadScriptTextDomain implements ILoadScriptTextDomainFunction
{
    use HasDomain;

    private string $handle;
    private string $path = '';

    public function rawArgs(): array
    {
        return [
            $this->getHandle(),
            $this->getDomain(),
            $this->getPath()
        ];
    }

    public function executeWithArgs(array $args): string|false
    {
        return load_script_textdomain(...$args);
    }

    public function handle(string $handle): static
    {
        $this->handle = $handle;
        return $this;
    }

    public function path(string $path): static
    {
        $this->path = $path;
        return $this;
    }

    public function getHandle(): string
    {
        return $this->handle;
    }

    public function getPath(): string
    {
        return $this->path;
    }
}
