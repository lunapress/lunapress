<?php

declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function\NumberFormatI18n;

use LunaPress\Wp\I18nContracts\Function\NumberFormatI18n\INumberFormatI18nFunction;

final class NumberFormatI18n implements INumberFormatI18nFunction
{
    private float $number;

    private int $decimals = 0;

    public function number(float $number): static
    {
        $this->number = $number;
        return $this;
    }

    public function decimals(int $decimals): static
    {
        $this->decimals = $decimals;
        return $this;
    }

    public function getNumber(): float
    {
        return $this->number;
    }

    public function getDecimals(): int
    {
        return $this->decimals;
    }

    public function rawArgs(): array
    {
        return [
            $this->getNumber(),
            $this->getDecimals(),
        ];
    }

    public function executeWithArgs(array $args): string
    {
        return number_format_i18n(...$args);
    }
}
