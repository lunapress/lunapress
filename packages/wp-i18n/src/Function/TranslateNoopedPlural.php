<?php

declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function;

use LunaPress\Wp\I18nContracts\Entity\INoopedPlural;
use LunaPress\Wp\I18nContracts\I18n;

final readonly class TranslateNoopedPlural
{
    public function __invoke(INoopedPlural $noopedPlural, int $count, string $domain = I18n::DEFAULT_DOMAIN): string
    {
        $noop = [
            'singular' => $noopedPlural->getSingle(),
            'plural'   => $noopedPlural->getPlural(),
            'context'  => $noopedPlural->getContext(),
            'domain'   => $noopedPlural->getDomain(),
        ];

        // phpcs:ignore WordPress.WP.I18n
        return translate_nooped_plural($noop, $count, $domain);
    }
}
