<?php
declare(strict_types=1);

namespace LunaPress\Config\DTO;

defined('ABSPATH') || exit;

final readonly class BuildConfig
{
    public function __construct(
        public ?string $pluginSlug = null,
        /** @var string[] */
        public array $distIgnore = []
    ) {
    }
}
