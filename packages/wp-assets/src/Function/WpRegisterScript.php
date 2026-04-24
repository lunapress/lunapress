<?php

declare(strict_types=1);

namespace LunaPress\Wp\Assets\Function;

use BackedEnum;
use LunaPress\FoundationContracts\Support\Wp\WpArray;
use LunaPress\FoundationContracts\Support\Wp\WpCaster;
use LunaPress\FoundationContracts\Support\Wp\WpUnset;
use LunaPress\Wp\AssetsContracts\DTO\WpRegisterScriptArgs;

final class WpRegisterScript
{
    public function __construct(
        private WpCaster $caster,
    ) {
    }

    /**
     * @param string[]|BackedEnum[] $deps
     */
    public function __invoke(
        string|BackedEnum $handle,
        string|BackedEnum $src = '',
        array $deps = [],
        string|bool|null $version = false,
        WpRegisterScriptArgs|WpArray|bool $args = WpArray::Empty
    ): bool
    {
        return wp_register_script(
            $this->caster->value($handle),
            $this->caster->value($src),
            $this->caster->list($deps),
            $version,
            $this->caster->value($args, $this->mapArgs(...)),
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function mapArgs(WpRegisterScriptArgs $args): array
    {
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
