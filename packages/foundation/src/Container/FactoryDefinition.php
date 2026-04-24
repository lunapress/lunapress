<?php

declare(strict_types=1);

namespace LunaPress\Foundation\Container;

use Closure;
use LunaPress\FoundationContracts\Container\Definition;

final readonly class FactoryDefinition implements Definition
{
    public function __construct(
        public Closure $factory
    ) {
    }
}
