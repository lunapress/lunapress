<?php

declare(strict_types=1);

namespace LunaPress\Wp\Assets\Function;

use BackedEnum;
use LunaPress\FoundationContracts\Support\Wp\WpArray;
use LunaPress\FoundationContracts\Support\Wp\WpCaster;
use LunaPress\FoundationContracts\Support\Wp\WpUnset;
use LunaPress\Wp\AssetsContracts\DTO\WpEnqueueScriptArgs;

final readonly class WpEnqueueScript
{
    public function __construct(
        private WpCaster $caster,
    ) {
    }

    /**
     * @param string[] $deps
     */
    public function __invoke(
        string|BackedEnum $handle,
        string|BackedEnum $src = '',
        array $deps = [],
        string|bool|null $version = false,
        WpEnqueueScriptArgs|WpArray|bool $args = WpArray::Empty
    ): void
    {
        wp_enqueue_script(
            $this->caster->value($handle),
            $this->caster->value($src),
            $deps,
            $version,
            $this->caster->value($args, $this->mapArgs(...))
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function mapArgs(WpEnqueueScriptArgs $args): array {
        $result = [];

        if ($args->inFooter !== WpUnset::Value) {
            $result['in_footer'] = $args->inFooter;
        }

        if ($args->strategy !== WpUnset::Value) {
            $result['strategy'] = $args->strategy->value;
        }

        if ($args->fetchPriority !== WpUnset::Value) {
            $result['fetchpriority'] = $args->fetchPriority->value;
        }

        return $result;
    }
}
