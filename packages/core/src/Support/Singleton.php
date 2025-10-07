<?php
declare(strict_types=1);

namespace LunaPress\Core\Support;

defined('ABSPATH') || exit;

abstract class Singleton
{
    private static array $instances = [];

    final public static function getInstance(): static
    {
        $class = static::class;

        if (!isset(static::$instances[$class])) {
            $instance                    = new static();
            static::$instances[ $class ] = $instance;
        }

        return static::$instances[$class];
    }

    final protected function __clone(): void {
    }
    final protected function __wakeup(): void {
    }
    final private function __construct()
    {
    }
}
