<?php

declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function;

use LunaPress\Wp\I18nContracts\I18n;

final readonly class RenderContextTranslate
{
    public function __invoke(string $text, string $context, string $domain = I18n::DEFAULT_DOMAIN): void
    {
        // phpcs:ignore WordPress.Security.EscapeOutput.UnsafePrintingFunction, WordPress.WP.I18n
        _ex($text, $context, $domain);
    }
}
