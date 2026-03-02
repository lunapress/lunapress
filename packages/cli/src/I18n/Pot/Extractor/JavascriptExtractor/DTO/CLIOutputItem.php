<?php
declare(strict_types=1);

namespace LunaPress\Cli\I18n\Pot\Extractor\JavascriptExtractor\DTO;

final readonly class CLIOutputItem
{
    public function __construct(
        public string $project,
        public string $path,
        /**
         * @var CLIChunkTranslation[] $files
         */
        public array $files,
    ) {
    }
}
