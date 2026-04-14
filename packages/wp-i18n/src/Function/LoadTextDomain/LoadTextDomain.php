<?php
declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function\LoadTextDomain;

use LunaPress\Wp\I18n\Trait\HasDomain;
use LunaPress\Wp\I18nContracts\Function\LoadTextDomain\ILoadTextDomainFunction;

defined('ABSPATH') || exit;

final class LoadTextDomain implements ILoadTextDomainFunction
{
    use HasDomain;

    private string $moFile;
    private ?string $locale = null;

    public function moFile(string $moFile): static
    {
        $this->moFile = $moFile;
        return $this;
    }

    public function getMoFile(): string
    {
        return $this->moFile;
    }

    public function locale(?string $locale): static
    {
        $this->locale = $locale;
        return $this;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function rawArgs(): array
    {
        return [
            $this->getDomain(),
            $this->getMoFile(),
            $this->getLocale()
        ];
    }

    public function executeWithArgs(array $args): bool
    {
        return load_textdomain(...$args);
    }
}
