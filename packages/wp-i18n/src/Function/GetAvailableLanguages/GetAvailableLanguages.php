<?php

declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function\GetAvailableLanguages;

use LunaPress\Wp\I18nContracts\Function\GetAvailableLanguages\IGetAvailableLanguagesFunction;

final class GetAvailableLanguages implements IGetAvailableLanguagesFunction
{
    private ?string $dir = null;

    public function dir(?string $dir): static
    {
        $this->dir = $dir;
        return $this;
    }

    public function getDir(): ?string
    {
        return $this->dir;
    }

    public function rawArgs(): array
    {
        return [
            $this->getDir(),
        ];
    }

    /**
     * @return string[]
     */
    public function executeWithArgs(array $args): array
    {
        return get_available_languages(...$args);
    }
}
