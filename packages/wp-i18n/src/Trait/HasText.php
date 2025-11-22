<?php
declare(strict_types=1);

namespace LunaPress\Wp\I18n\Trait;

defined('ABSPATH') || exit;

trait HasText
{
    private string $text;

    public function text(string $text): static
    {
        $this->text = $text;
        return $this;
    }

    public function getText(): string
    {
        return $this->text;
    }
}
