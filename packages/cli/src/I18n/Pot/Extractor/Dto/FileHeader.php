<?php
declare(strict_types=1);

namespace LunaPress\Cli\I18n\Pot\Extractor\Dto;

final readonly class FileHeader
{
    /**
     * @param string $filePath
     * @param array<string, HeaderField> $headers
     * @param bool $isTheme
     */
    public function __construct(
        public string $filePath,
        public array $headers,
        public bool $isTheme
    ) {
    }
}
