<?php

declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function;

use LunaPress\Wp\I18nContracts\I18n;

final readonly class EscAttrRender
{
    public function __invoke(string $text, string $domain = I18n::DEFAULT_DOMAIN): void
    {
        // phpcs:ignore WordPress.WP.I18n
        esc_attr_e($text, $domain);
    }
}
