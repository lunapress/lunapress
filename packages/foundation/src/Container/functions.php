<?php
declare(strict_types=1);

// @phpcs:ignore LunaPressStandard.WP.AbspathAfterNamespace
namespace LunaPress\Foundation\Container;

function autowire(string $class): AutowireDefinition
{
    return new AutowireDefinition($class);
}

function factory(callable $callback): FactoryDefinition
{
    return new FactoryDefinition($callback(...));
}
