<?php

declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function;

use LunaPress\Wp\I18nContracts\DTO\NoopedPlural;
use LunaPress\Wp\I18nContracts\I18n;

final readonly class ContextNoopPluralTranslate
{
    public function __invoke(string $single, string $plural, string $context, string $domain = I18n::DEFAULT_DOMAIN): NoopedPlural
    {
        // phpcs:ignore WordPress.WP.I18n
        $noop = _nx_noop($single, $plural, $context, $domain);

        return new NoopedPlural(
            $noop['singular'],
            $noop['plural'],
            $noop['context'],
            $noop['domain']
        );
    }
}
