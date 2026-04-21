<?php

declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function\SwitchToLocale;

use LunaPress\Wp\I18nContracts\Function\SwitchToLocale\ISwitchToLocaleFunction;

final class SwitchToLocale implements ISwitchToLocaleFunction
{
    private string $locale;

    public function locale(string $locale): static
    {
        $this->locale = $locale;
        return $this;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function rawArgs(): array
    {
        return [
            $this->getLocale(),
        ];
    }

    public function executeWithArgs(array $args): bool
    {
        return switch_to_locale(...$args);
    }
}
