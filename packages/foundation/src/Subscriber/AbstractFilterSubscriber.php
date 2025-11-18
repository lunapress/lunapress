<?php
declare(strict_types=1);

namespace LunaPress\Foundation\Subscriber;

use LunaPress\FoundationContracts\Subscriber\IFilterSubscriber;

defined('ABSPATH') || exit;

abstract readonly class AbstractFilterSubscriber extends AbstractSubscriber implements IFilterSubscriber
{
}
