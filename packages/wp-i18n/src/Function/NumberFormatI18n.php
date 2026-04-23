<?php

declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function;

final readonly class NumberFormatI18n
{
    public function __invoke(float $number, int $decimals = 0): string
    {
        return number_format_i18n($number, $decimals);
    }
}
