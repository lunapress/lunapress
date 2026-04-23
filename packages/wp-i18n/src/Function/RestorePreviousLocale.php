<?php

declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function;

final readonly class RestorePreviousLocale
{
    public function __invoke(): string|false
    {
        return restore_previous_locale();
    }
}
