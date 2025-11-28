<?php
declare(strict_types=1);

namespace LunaPress\Cli\I18n\Pot\Extractor;

interface IExtractor
{
    /**
     * @param string[] $files
     * @param string $source
     * @return ExtractedMessage[]
     */
    public function extract(array $files, string $source): array;

    public function supports(string $filePath): bool;
}
