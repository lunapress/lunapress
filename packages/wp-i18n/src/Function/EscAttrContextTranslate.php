<?php

declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function;

use LunaPress\Wp\I18nContracts\I18n;

final readonly class EscAttrContextTranslate
{
    public function __invoke(string $text, string $context, string $domain = I18n::DEFAULT_DOMAIN): string
    {
        // phpcs:ignore WordPress.WP.I18n
        return esc_attr_x($text, $context, $domain);
    }
}
