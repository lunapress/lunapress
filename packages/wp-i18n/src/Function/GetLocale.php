<?php

declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function;

final readonly class GetLocale
{
    public function __invoke(): string
    {
        return get_locale();
    }
}
