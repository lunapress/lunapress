<?php
declare(strict_types=1);

namespace LunaPress\Core\Subscriber;

use LunaPress\CoreContracts\Subscriber\DelayedSubscriber;

defined('ABSPATH') || exit;

abstract class AbstractDelayedSubscriber implements DelayedSubscriber
{
    public static function afterPriority(): int
    {
        return 10;
    }

    public static function afterArgs(): int
    {
        return 1;
    }
}
