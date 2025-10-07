<?php
declare(strict_types=1);

namespace LunaPress\Core\Hook;

use Attribute;
use LunaPress\CoreContracts\Hook\IHook;

defined('ABSPATH') || exit;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
final readonly class Hook implements IHook
{
    public function __construct(
        private string $name,
        private int $priority = 10,
        private int $args = 1,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function getAcceptedArgs(): int
    {
        return $this->args;
    }
}
