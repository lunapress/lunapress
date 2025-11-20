<?php
declare(strict_types=1);

namespace LunaPress\Core\Support\WpFunction;

use BackedEnum;
use LunaPress\FoundationContracts\Support\IExecutableFunction;
use LunaPress\FoundationContracts\Support\WpFunction\IWpFunctionArgs;
use LunaPress\FoundationContracts\Support\WpFunction\IWpFunctionExecutor;
use LunaPress\FoundationContracts\Support\WpFunction\WpArray;
use LunaPress\FoundationContracts\Support\WpFunction\WpUnset;
use Stringable;

defined('ABSPATH') || exit;

/**
 * @template TResult
 */
final readonly class WpFunctionExecutor implements IWpFunctionExecutor
{
    /**
     * @param IExecutableFunction<TResult> $function
     * @return TResult
     */
    public function execute(IExecutableFunction $function): mixed
    {
        $rawArgs = $function->rawArgs();

        $args = $this->normalizeArgs($rawArgs);

        return $function->executeWithArgs($args);
    }

    /**
     * Top level of WP function arguments
     * Here we must not violate the order of arguments
     *
     * @param array $args
     * @return array
     */
    private function normalizeArgs(array $args): array
    {
        return array_map(
            fn($arg) => $this->normalizeArg($arg),
            $args
        );
    }

    /**
     * Processing the WP argument of the top-level function
     * Must return some value
     *
     * @param mixed $arg
     * @return mixed
     */
    private function normalizeArg(mixed $arg): mixed
    {
        // We process arguments that are typed by classes,
        // but for WP they must be strings
        if ($arg instanceof Stringable) {
            return (string) $arg;
        }

        if ($arg instanceof BackedEnum) {
            return $arg->value;
        }

        if ($arg === WpArray::Empty) {
            return [];
        }

        if ($arg instanceof IWpFunctionArgs) {
            return $this->normalizeArray($arg->toArray());
        }

        if (is_array($arg)) {
            return $this->normalizeArray($arg);
        }

        return $arg;
    }

    /**
     * Processing the WP function argument, which is an array
     * There are optional values that you need to be able to not pass at all
     *
     * @param array $data
     * @return array
     */
    private function normalizeArray(array $data): array
    {
        $mapped = array_map(
            fn($item) => $this->normalizeArg($item),
            $data
        );

        return array_filter(
            $mapped,
            static fn($item) => $item !== WpUnset::Value
        );
    }
}
