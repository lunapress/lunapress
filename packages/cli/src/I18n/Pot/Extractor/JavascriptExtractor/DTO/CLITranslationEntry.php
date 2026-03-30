<?php
declare(strict_types=1);

namespace LunaPress\Cli\I18n\Pot\Extractor\JavascriptExtractor\DTO;

final readonly class CLITranslationEntry
{
    public function __construct(
        public string $domain,
        public string $sourceFile,
        public int $line,
        public ?string $text = null,
        public ?string $context = null,
        public ?string $single = null,
        public ?string $plural = null,
        public ?int $number = null,
    ) {
    }
}
