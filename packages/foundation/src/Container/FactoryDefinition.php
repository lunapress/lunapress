<?php
declare(strict_types=1);

namespace LunaPress\Foundation\Container;

use Closure;
use LunaPress\FoundationContracts\Container\IDefinition;

defined('ABSPATH') || exit;

final readonly class FactoryDefinition implements IDefinition
{
    public function __construct(
        public Closure $factory
    ) {
    }
}
