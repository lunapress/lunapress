<?php
declare(strict_types=1);

namespace LunaPress\Foundation\Support;

use Exception;

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
    /**
     * @throws Exception
     */
    final public function __wakeup(): void
    {
        throw new Exception('Cannot unserialize a singleton.');
    }
    final private function __construct()
    {
    }
}
