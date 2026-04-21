<?php

declare(strict_types=1);

namespace LunaPress\Wp\I18n\Trait;

trait HasNoopPlural
{
    private string $single;
    private string $plural;

    public function single(string $single): static
    {
        $this->single = $single;
        return $this;
    }

    public function plural(string $plural): static
    {
        $this->plural = $plural;
        return $this;
    }

    public function getSingle(): string
    {
        return $this->single;
    }

    public function getPlural(): string
    {
        return $this->plural;
    }
}
