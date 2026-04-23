<?php

declare(strict_types=1);

namespace LunaPress\Frontend\Modules\Vite\DTO;

use LunaPress\FrontendContracts\Vite\IViteEntryPoint;

class WpViteEntryPoint implements IViteEntryPoint
{
    // @TODO: enum in name
    public function __construct(
        private string $name,
    ) {
    }

    public static function of(string $name): self
    {
        return new self($name);
    }

    public function getName(): string
    {
        return $this->name;
    }
}
