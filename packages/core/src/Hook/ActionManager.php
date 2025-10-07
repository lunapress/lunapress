<?php
declare(strict_types=1);

namespace LunaPress\Core\Hook;

use LunaPress\CoreContracts\Hook\IActionManager;

defined('ABSPATH') || exit;

final readonly class ActionManager implements IActionManager
{
    public function add(string $name, callable $callback, int $priority = 10, int $args = 1): void
    {
        add_action($name, $callback, $priority, $args);
    }

    public function do(string $name, ...$args): void
    {
        do_action($name, ...$args);
    }
}
