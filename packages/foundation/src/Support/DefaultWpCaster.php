<?php

declare(strict_types=1);

namespace LunaPress\Foundation\Support;

use BackedEnum;
use LunaPress\FoundationContracts\Support\Wp\WpArgument;
use LunaPress\FoundationContracts\Support\Wp\WpArray;
use LunaPress\FoundationContracts\Support\Wp\WpCaster;
use function array_map;

final readonly class DefaultWpCaster implements WpCaster
{
    /**
     * @param null|callable(WpArgument): mixed $argumentMapper
     */
    public function value(mixed $value, ?callable $argumentMapper = null): mixed
    {
        if ($value === WpArray::Empty) {
            return [];
        }

        if ($value instanceof BackedEnum) {
            return $value->value;
        }

        if ($argumentMapper !== null && $value instanceof WpArgument) {
            return $argumentMapper($value);
        }

        return $value;
    }

    /**
     * @param array<mixed> $list
     * @param null|callable(WpArgument): mixed $argumentMapper
     * @return array<mixed>
     */
    public function list(array $list, ?callable $argumentMapper = null): array
    {
        return array_map($this->value(...), $list);
    }

    public function asString(BackedEnum|string $value): string
    {
        if ($value instanceof BackedEnum) {
            return (string) $value->value;
        }

        return (string) $value;
    }
}
