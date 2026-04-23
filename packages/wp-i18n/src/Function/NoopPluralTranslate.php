<?php

declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function;

use LunaPress\Wp\I18n\Entity\NoopedPlural;

final readonly class NoopPluralTranslate
{
    public function __invoke(string $single, string $plural, string $domain = null): NoopedPlural
    {
        // phpcs:ignore WordPress.WP.I18n
        $noop = _n_noop($single, $plural, $domain);

        return new NoopedPlural(
            $noop['singular'],
            $noop['plural'],
            $noop['context'],
            $noop['domain']
        );
    }
}
