<?php
declare(strict_types=1);

namespace LunaPress\Cli\I18n\Pot\Extractor\JavascriptExtractor\DTO;

final readonly class CLIChunkTranslation
{
    public function __construct(
        public string $chunkPath,
        /**
         * @var CLITranslationEntry[] $translationEntries
         */
        public array $translationEntries,
    )
    {
    }
}
