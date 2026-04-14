<?php
declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function\IsTextDomainLoaded;

use LunaPress\Wp\I18n\Trait\HasDomain;
use LunaPress\Wp\I18nContracts\Function\IsTextDomainLoaded\IIsTextDomainLoadedFunction;

defined('ABSPATH') || exit;

final class IsTextDomainLoaded implements IIsTextDomainLoadedFunction
{
    use HasDomain;

    public function rawArgs(): array
    {
        return [
            $this->getDomain()
        ];
    }

    public function executeWithArgs(array $args): bool
    {
        return is_textdomain_loaded(...$args);
    }
}
