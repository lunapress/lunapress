<?php

declare(strict_types=1);

namespace LunaPress\Config\DTO;

final readonly class BuildConfig
{
    public function __construct(
        public ?string $pluginSlug = null,
        /** @var string[] */
        public array $distIgnore = []
    ) {
    }
}
