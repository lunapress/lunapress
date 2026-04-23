<?php

declare(strict_types=1);

namespace LunaPress\Wp\Assets\Function;

use BackedEnum;
use LunaPress\FoundationContracts\Support\WpFunction\IWpCaster;
use LunaPress\FoundationContracts\Support\WpFunction\WpArray;
use LunaPress\Wp\AssetsContracts\DTO\WpRegisterScriptArgs;

final class WpRegisterScript
{
    public function __construct(
        private IWpCaster $caster,
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
            $this->caster->value($args)
        );
    }
}
