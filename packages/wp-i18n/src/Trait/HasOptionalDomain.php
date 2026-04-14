<?php
declare(strict_types=1);

namespace LunaPress\Wp\I18n\Trait;

defined('ABSPATH') || exit;

trait HasOptionalDomain
{
    private ?string $domain = null;

    public function domain(?string $domain): static
    {
        $this->domain = $domain;
        return $this;
    }

    public function getDomain(): ?string
    {
        return $this->domain;
    }
}
