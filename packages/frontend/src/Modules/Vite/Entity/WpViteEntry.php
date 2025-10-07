<?php
declare(strict_types=1);

namespace LunaPress\Frontend\Modules\Vite\Entity;

use LunaPress\FrontendContracts\Vite\IViteEntry;

defined('ABSPATH') || exit;

final readonly class WpViteEntry implements IViteEntry
{
    public function __construct(
        private string $name,
        private string $file,
        private bool $isEntry = false,
        private array $css = [],
        private ?string $src = null,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getFile(): string
    {
        return $this->file;
    }

    public function isEntry(): bool
    {
        return $this->isEntry;
    }

    /** @return string[] */
    public function getCss(): array
    {
        return $this->css;
    }

    public function getSrc(): ?string
    {
        return $this->src;
    }
}
