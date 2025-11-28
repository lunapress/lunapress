<?php
declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function\ContextPluralTranslate;

use LunaPress\Wp\I18n\Trait\HasContext;
use LunaPress\Wp\I18n\Trait\HasDomain;
use LunaPress\Wp\I18n\Trait\HasPlural;
use LunaPress\Wp\I18nContracts\Function\ContextPluralTranslate\IContextPluralTranslateFunction;

defined('ABSPATH') || exit;

final class ContextPluralTranslate implements IContextPluralTranslateFunction
{
    use HasDomain;
    use HasPlural;
    use HasContext;

    public function rawArgs(): array
    {
        return [
            $this->getSingle(),
            $this->getPlural(),
            $this->getNumber(),
            $this->getContext(),
            $this->getDomain(),
        ];
    }

    public function executeWithArgs(array $args)
    {
        // phpcs:ignore WordPress.WP.I18n
        return _nx(...$args);
    }
}
