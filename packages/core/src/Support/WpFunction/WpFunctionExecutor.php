<?php
declare(strict_types=1);

namespace LunaPress\Core\Support\WpFunction;

use LunaPress\CoreContracts\Support\ExecutableFunction;
use LunaPress\CoreContracts\Support\WpFunction\WpFunctionArgs;
use LunaPress\CoreContracts\Support\WpFunction\IWpFunctionExecutor;

defined('ABSPATH') || exit;

/**
 * @template TResult
 */
final readonly class WpFunctionExecutor implements IWpFunctionExecutor
{
    /**
     * @param ExecutableFunction<TResult> $function
     * @return TResult
     */
    public function execute(ExecutableFunction $function): mixed
    {
        $args = $this->normalizeArgs($function->rawArgs());

        $result = $function->executeWithArgs($args);

        return $result;
    }

    private function normalizeArgs(array $args): array
    {
        return array_map(
            fn($arg) => $this->normalizeArg($arg),
            $args
        );
    }

    private function normalizeArg(mixed $arg): mixed
    {
        if ($arg instanceof WpFunctionArgs) {
            return $this->filterNulls($arg->toArray());
        }

        if (is_array($arg)) {
            return array_map(fn($arg) => $this->normalizeArg($arg), $arg);
        }

        return $arg;
    }

    private function filterNulls(array $data): array
    {
        return array_filter($data, static fn($value) => $value !== null);
    }
}
