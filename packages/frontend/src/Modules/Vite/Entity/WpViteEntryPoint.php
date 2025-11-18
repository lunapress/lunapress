<?php
declare(strict_types=1);

namespace LunaPress\Frontend\Modules\Vite\Entity;

use LunaPress\FrontendContracts\Vite\IViteEntryPoint;

defined('ABSPATH') || exit;

class WpViteEntryPoint implements IViteEntryPoint
{
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
