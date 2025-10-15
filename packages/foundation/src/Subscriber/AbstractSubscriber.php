<?php
declare(strict_types=1);

namespace LunaPress\Foundation\Subscriber;

use LogicException;
use LunaPress\FoundationContracts\Subscriber\Subscriber;

defined('ABSPATH') || exit;

abstract readonly class AbstractSubscriber implements Subscriber
{
    public function callback(): callable
    {
        if (!method_exists($this, '__invoke')) {
            throw new LogicException(static::class . ' must implement __invoke()');
        }

        return $this(...);
    }
}
