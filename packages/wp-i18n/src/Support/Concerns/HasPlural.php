<?php
declare(strict_types=1);

namespace LunaPress\Wp\I18n\Support\Concerns;

defined('ABSPATH') || exit;

trait HasPlural
{
    private string $single;
    private string $plural;
    private int $number;

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

    public function number(int $number): static
    {
        $this->number = $number;
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

    public function getNumber(): int
    {
        return $this->number;
    }
}
