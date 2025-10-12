<?php
declare(strict_types=1);

namespace LunaPress\Foundation\Container;

use LunaPress\FoundationContracts\Container\IDefinition;

defined('ABSPATH') || exit;

final readonly class AutowireDefinition implements IDefinition
{
    public function __construct(
        public string $class
    ) {
    }
}
