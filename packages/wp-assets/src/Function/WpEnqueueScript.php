<?php

declare(strict_types=1);

namespace LunaPress\Wp\Assets\Function;

use BackedEnum;
use LunaPress\FoundationContracts\Support\WpFunction\IWpCaster;
use LunaPress\FoundationContracts\Support\WpFunction\WpArray;
use LunaPress\Wp\AssetsContracts\DTO\WpEnqueueScriptArgs;

final readonly class WpEnqueueScript
{
    public function __construct(
        private IWpCaster $caster,
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
            $this->caster->value($args)
        );
    }
}
