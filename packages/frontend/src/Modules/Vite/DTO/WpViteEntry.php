<?php

declare(strict_types=1);

namespace LunaPress\Frontend\Modules\Vite\DTO;

use LunaPress\FrontendContracts\Vite\IViteEntry;
use function str_ends_with;

final readonly class WpViteEntry implements IViteEntry
{
    private bool $isCss;

    /**
     * @param string[] $css
     */
    public function __construct(
        private string $name,
        private string $file,
        private bool $isEntry = false,
        private array $css = [],
        private ?string $src = null,
    ) {
        $this->isCss = str_ends_with($this->file, '.css');
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

    public function isCss(): bool
    {
        return $this->isCss;
    }
}
