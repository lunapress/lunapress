<?php

declare(strict_types=1);

namespace LunaPress\Cli\I18n\Pot\Extractor\FileHeaderExtractor\Dto;

final readonly class HeaderField
{
    public function __construct(
        public string $name,
        public string $value,
        public int $line
    ) {
    }

    public function isEmpty(): bool
    {
        return empty($this->value);
    }
}
