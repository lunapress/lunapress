<?php

declare(strict_types=1);

namespace LunaPress\Foundation\Subscriber;

use LunaPress\FoundationContracts\Subscriber\IActionSubscriber;

abstract readonly class AbstractActionSubscriber extends AbstractSubscriber implements IActionSubscriber
{
}
