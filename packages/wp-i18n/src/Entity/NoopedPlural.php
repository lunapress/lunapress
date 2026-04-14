<?php
declare(strict_types=1);

namespace LunaPress\Wp\I18n\Entity;

use LunaPress\Wp\I18nContracts\Entity\INoopedPlural;

defined('ABSPATH') || exit;

final readonly class NoopedPlural implements INoopedPlural
{
    public function __construct(
        private string $single,
        private string $plural,
        private ?string $context = null,
        private ?string $domain = null
    ) {
    }

    public function getSingle(): string
    {
        return $this->single;
    }

    public function getPlural(): string
    {
        return $this->plural;
    }

    public function getContext(): ?string
    {
        return $this->context;
    }

    public function getDomain(): ?string
    {
        return $this->domain;
    }
}
