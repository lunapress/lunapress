<?php

declare(strict_types=1);

namespace LunaPress\Foundation\Subscriber;

use LogicException;
use LunaPress\FoundationContracts\Subscriber\ISubscriber;
use function method_exists;

abstract readonly class AbstractSubscriber implements ISubscriber
{
    public function callback(): callable
    {
        if (!method_exists($this, '__invoke')) {
            throw new LogicException(static::class . ' must implement __invoke()');
        }

        return $this(...);
    }
}
