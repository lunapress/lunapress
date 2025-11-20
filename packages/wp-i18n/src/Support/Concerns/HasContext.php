<?php
declare(strict_types=1);

namespace LunaPress\Wp\I18n\Support\Concerns;

defined('ABSPATH') || exit;

trait HasContext
{
    private string $context;

    public function context(string $context): static
    {
        $this->context = $context;
        return $this;
    }

    public function getContext(): string
    {
        return $this->context;
    }
}
