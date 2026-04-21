<?php

declare(strict_types=1);

namespace LunaPress\Cli\I18n\Pot\Extractor\FileHeaderExtractor\Dto;

final readonly class FileHeader
{
    /**
     * @param array<string, HeaderField> $headers
     */
    public function __construct(
        public string $filePath,
        public array $headers,
        public bool $isTheme
    ) {
    }
}
