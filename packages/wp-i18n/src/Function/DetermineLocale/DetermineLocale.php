<?php

declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function\DetermineLocale;

use LunaPress\Wp\I18nContracts\Function\DetermineLocale\IDetermineLocaleFunction;

final class DetermineLocale implements IDetermineLocaleFunction
{
    public function rawArgs(): array
    {
        return [];
    }

    public function executeWithArgs(array $args): string
    {
        return determine_locale();
    }
}
