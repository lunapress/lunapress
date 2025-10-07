<?php
declare(strict_types=1);

namespace LunaPress\Core\Hook;

use LunaPress\CoreContracts\Hook\IFilterManager;

defined('ABSPATH') || exit;

final readonly class FilterManager implements IFilterManager
{
    public function add(string $name, callable $callback, int $priority = 10, int $args = 1): void
    {
        add_filter($name, $callback, $priority, $args);
    }

    public function apply(string $name, mixed $value, mixed ...$args): mixed
    {
        return apply_filters($name, $value, ...$args);
    }
}
