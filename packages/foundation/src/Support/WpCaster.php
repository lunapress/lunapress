<?php

declare(strict_types=1);

namespace LunaPress\Foundation\Support;

use BackedEnum;
use LunaPress\FoundationContracts\Support\WpFunction\IWpArrayable;
use LunaPress\FoundationContracts\Support\WpFunction\IWpCaster;
use LunaPress\FoundationContracts\Support\WpFunction\IWpResolvable;
use LunaPress\FoundationContracts\Support\WpFunction\WpArray;
use function array_map;

final readonly class WpCaster implements IWpCaster
{
    public function value(mixed $value): mixed
    {
        return match (true) {
            $value === WpArray::Empty => [],
            $value instanceof BackedEnum => $value->value,
            $value instanceof IWpResolvable => $value->toWpValue(),
            $value instanceof IWpArrayable => $value->toWpArray(),
            default => $value,
        };
    }

    /**
     * @param array<mixed> $list
     * @return array<mixed>
     */
    public function list(array $list): array
    {
        return array_map($this->value(...), $list);
    }
}
