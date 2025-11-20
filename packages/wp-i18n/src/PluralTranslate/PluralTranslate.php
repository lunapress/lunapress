<?php
declare(strict_types=1);

namespace LunaPress\Wp\I18n\PluralTranslate;

use LunaPress\Wp\I18n\Support\Concerns\HasDomain;
use LunaPress\Wp\I18n\Support\Concerns\HasPlural;
use LunaPress\Wp\I18nContracts\PluralTranslate\IPluralTranslateFunction;

defined('ABSPATH') || exit;

final class PluralTranslate implements IPluralTranslateFunction
{
    use HasDomain;
    use HasPlural;

    public function rawArgs(): array
    {
        return [
            $this->getSingle(),
            $this->getPlural(),
            $this->getNumber(),
            $this->getDomain(),
        ];
    }

    public function executeWithArgs(array $args): string
    {
        // phpcs:ignore WordPress.WP.I18n
        return _n(...$args);
    }
}
