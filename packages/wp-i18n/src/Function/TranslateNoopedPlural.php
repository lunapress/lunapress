<?php

declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function;

use LunaPress\Wp\I18nContracts\DTO\NoopedPlural;
use LunaPress\Wp\I18nContracts\I18n;

final readonly class TranslateNoopedPlural
{
    public function __invoke(NoopedPlural $noopedPlural, int $count, string $domain = I18n::DEFAULT_DOMAIN): string
    {
        $noop = [
            'singular' => $noopedPlural->single,
            'plural'   => $noopedPlural->plural,
            'context'  => $noopedPlural->context,
            'domain'   => $noopedPlural->domain,
        ];

        // phpcs:ignore WordPress.WP.I18n
        return translate_nooped_plural($noop, $count, $domain);
    }
}
