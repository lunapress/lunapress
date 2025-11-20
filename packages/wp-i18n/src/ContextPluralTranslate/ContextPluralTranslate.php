<?php
declare(strict_types=1);

namespace LunaPress\Wp\I18n\ContextPluralTranslate;

use LunaPress\Wp\I18n\Support\Concerns\HasContext;
use LunaPress\Wp\I18n\Support\Concerns\HasDomain;
use LunaPress\Wp\I18n\Support\Concerns\HasPlural;
use LunaPress\Wp\I18nContracts\ContextPluralTranslate\IContextPluralTranslateFunction;

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
