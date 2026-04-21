<?php

declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function\GetLocale;

use LunaPress\Wp\I18nContracts\Function\GetLocale\IGetLocaleFunction;

final class GetLocale implements IGetLocaleFunction
{
    public function rawArgs(): array
    {
        return [];
    }

    public function executeWithArgs(array $args): string
    {
        return get_locale();
    }
}
