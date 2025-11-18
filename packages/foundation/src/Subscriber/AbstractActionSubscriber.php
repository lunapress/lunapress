<?php
declare(strict_types=1);

namespace LunaPress\Foundation\Subscriber;

use LunaPress\FoundationContracts\Subscriber\IActionSubscriber;

defined('ABSPATH') || exit;

abstract readonly class AbstractActionSubscriber extends AbstractSubscriber implements IActionSubscriber
{
}
