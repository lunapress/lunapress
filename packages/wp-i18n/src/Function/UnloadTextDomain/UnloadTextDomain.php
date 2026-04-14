<?php
declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function\UnloadTextDomain;

use LunaPress\Wp\I18n\Trait\HasDomain;
use LunaPress\Wp\I18nContracts\Function\UnloadTextDomain\IUnloadTextDomainFunction;

defined('ABSPATH') || exit;

final class UnloadTextDomain implements IUnloadTextDomainFunction
{
    use HasDomain;

    private bool $reloadable = false;

    public function reloadable(bool $reloadable): static
    {
        $this->reloadable = $reloadable;
        return $this;
    }

    public function isReloadable(): bool
    {
        return $this->reloadable;
    }

    public function rawArgs(): array
    {
        return [
            $this->getDomain(),
            $this->isReloadable()
        ];
    }

    public function executeWithArgs(array $args): bool
    {
        return unload_textdomain(...$args);
    }
}
