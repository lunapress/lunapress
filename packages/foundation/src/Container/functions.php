<?php
declare(strict_types=1);

namespace LunaPress\Foundation\Container;

use Closure;

defined('ABSPATH') || exit;

function autowire(string $class): AutowireDefinition
{
    return new AutowireDefinition($class);
}

function factory(callable $callback): FactoryDefinition
{
    return new FactoryDefinition(Closure::fromCallable($callback));
}
