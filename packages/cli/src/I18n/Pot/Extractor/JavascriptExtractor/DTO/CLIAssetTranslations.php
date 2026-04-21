<?php

declare(strict_types=1);

namespace LunaPress\Cli\I18n\Pot\Extractor\JavascriptExtractor\DTO;

final readonly class CLIAssetTranslations
{
    public function __construct(
        public string $assetPath,
        /**
         * @var CLITranslationEntry[] $entries
         */
        public array $entries,
    )
    {
    }
}
