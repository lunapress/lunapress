<?php
declare(strict_types=1);

namespace LunaPress\Wp\I18n\Support\Concerns;

defined('ABSPATH') || exit;

trait HasDomain
{
    private string $domain = 'default';

    public function domain(string $domain): static
    {
        $this->domain = $domain;
        return $this;
    }

    public function getDomain(): string
    {
        return $this->domain;
    }
}
