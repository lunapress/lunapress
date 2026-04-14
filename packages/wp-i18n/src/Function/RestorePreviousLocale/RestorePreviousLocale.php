<?php
declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function\RestorePreviousLocale;

use LunaPress\Wp\I18nContracts\Function\RestorePreviousLocale\IRestorePreviousLocaleFunction;

defined('ABSPATH') || exit;

final class RestorePreviousLocale implements IRestorePreviousLocaleFunction
{
    public function rawArgs(): array
    {
        return [];
    }

    public function executeWithArgs(array $args): string|false
    {
        return restore_previous_locale();
    }
}
