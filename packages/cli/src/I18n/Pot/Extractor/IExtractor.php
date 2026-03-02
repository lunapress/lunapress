<?php
declare(strict_types=1);

namespace LunaPress\Cli\I18n\Pot\Extractor;

interface IExtractor
{
    /**
     * @param string[] $files
     * @param string $source
     * @param string[] $domains
     * @param string[] $ignoreDomains
     * @return ExtractedMessage[]
     */
    public function extract(array $files, string $source, array $domains = [], array $ignoreDomains = []): array;

    public function supports(string $filePath): bool;

    public function getPatterns(): array;
}
