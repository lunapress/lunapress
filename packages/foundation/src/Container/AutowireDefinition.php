<?php

declare(strict_types=1);

namespace LunaPress\Foundation\Container;

use LunaPress\FoundationContracts\Container\Definition;

final readonly class AutowireDefinition implements Definition
{
    public function __construct(
        public string $class
    ) {
    }
}
