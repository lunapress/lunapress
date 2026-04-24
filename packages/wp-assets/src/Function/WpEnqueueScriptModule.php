<?php

declare(strict_types=1);

namespace LunaPress\Wp\Assets\Function;

use BackedEnum;
use LunaPress\FoundationContracts\Support\Wp\WpArray;
use LunaPress\FoundationContracts\Support\Wp\WpCaster;
use LunaPress\FoundationContracts\Support\Wp\WpUnset;
use LunaPress\Wp\AssetsContracts\DTO\ScriptModuleDependency;
use LunaPress\Wp\AssetsContracts\DTO\WpEnqueueScriptModuleArgs;

final readonly class WpEnqueueScriptModule
{
    public function __construct(
        private WpCaster $caster,
    ) {
    }

    /**
     * @param string[]|ScriptModuleDependency[] $deps
     */
    public function __invoke(
        string|BackedEnum $id,
        string|BackedEnum $src = '',
        array $deps = [],
        string|false|null $version = false,
        WpEnqueueScriptModuleArgs|WpArray $args = WpArray::Empty
    ): void
    {
        wp_enqueue_script_module(
            $this->caster->value($id),
            $this->caster->value($src),
            $this->caster->list($deps),
            $version,
            $this->caster->value($args, $this->mapArgs(...))
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function mapArgs(WpEnqueueScriptModuleArgs $args): array
    {
        $result = [];

        if ($args->inFooter !== WpUnset::Value) {
            $result['footer'] = $args->inFooter;
        }

        if ($args->fetchPriority !== WpUnset::Value) {
            $result['priority'] = $args->fetchPriority;
        }

        return $result;
    }
}
