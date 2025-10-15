<?php
declare(strict_types=1);

namespace LunaPress\Foundation\Subscriber;

use LunaPress\FoundationContracts\Subscriber\ActionSubscriber;

defined('ABSPATH') || exit;

abstract readonly class AbstractActionSubscriber extends AbstractSubscriber implements ActionSubscriber
{
}
