<?php

declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function;

use LunaPress\Wp\I18nContracts\I18n;

final readonly class PluralTranslate
{
    public function __invoke(string $single, string $plural, int $number, string $domain = I18n::DEFAULT_DOMAIN): string
    {
        // phpcs:ignore WordPress.WP.I18n
        return _n($single, $plural, $number, $domain);
    }
}
