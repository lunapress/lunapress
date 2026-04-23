<?php

declare(strict_types=1);

namespace LunaPress\Wp\Assets\Function;

use BackedEnum;
use LunaPress\FoundationContracts\Support\WpFunction\IWpCaster;
use LunaPress\Wp\AssetsContracts\Enum\StyleMedia;

final readonly class WpEnqueueStyle
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
        array             $deps = [],
        string|bool|null  $version = false,
        StyleMedia|string $media = StyleMedia::All
    ): void
    {
        wp_enqueue_style(
            $this->caster->value($handle),
            $this->caster->value($src),
            $this->caster->value($deps),
            $version,
            $this->caster->value($media)
        );
    }
}
