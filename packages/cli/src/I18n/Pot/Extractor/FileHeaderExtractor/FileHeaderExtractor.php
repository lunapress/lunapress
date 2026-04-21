<?php

declare(strict_types=1);

namespace LunaPress\Cli\I18n\Pot\Extractor\FileHeaderExtractor;

use Gettext\Translation;
use LunaPress\Cli\I18n\Constants;
use LunaPress\Cli\I18n\Pot\Extractor\ExtractedMessage;
use LunaPress\Cli\I18n\Pot\Extractor\ExtractorPatternMatchTrait;
use LunaPress\Cli\I18n\Pot\Extractor\FileHeaderExtractor\Dto\FileHeader;
use LunaPress\Cli\I18n\Pot\Extractor\IExtractor;
use LunaPress\Cli\I18n\Pot\Scanner\ProjectMetadataScanner;
use Symfony\Component\Filesystem\Path;
use function array_merge;
use function in_array;
use function sprintf;

final readonly class FileHeaderExtractor implements IExtractor
{
    use ExtractorPatternMatchTrait;

    public function __construct(private ProjectMetadataScanner $scanner)
    {
    }

    public const array EXTRACTABLE_HEADERS = [
        ProjectMetadataScanner::HEADER_PLUGIN_NAME,
        ProjectMetadataScanner::HEADER_PLUGIN_URI,
        ProjectMetadataScanner::HEADER_THEME_NAME,
        ProjectMetadataScanner::HEADER_THEME_URI,
        ProjectMetadataScanner::HEADER_DESCRIPTION,
        ProjectMetadataScanner::HEADER_AUTHOR,
        ProjectMetadataScanner::HEADER_AUTHOR_URI,
    ];

    public function getPatterns(): array
    {
        return ['*.php', 'style.css'];
    }

    public function extract(array $files, string $source, array $domains = [], array $ignoreDomains = []): array
    {
        /** @var ExtractedMessage[] $messages */
        $messages = [];

        $projectData = $this->scanner->scan($files, $source);

        if ($projectData !== null) {
            $messages = array_merge($messages, $this->createExtractedMessages($projectData, $source, $domains, $ignoreDomains));
        }

        return $messages;
    }

    private function createExtractedMessages(FileHeader $fileHeader, string $source, array $domains, array $ignoreDomains): array
    {
        $messages = [];
        $domain   = Constants::DEFAULT_DOMAIN;

        $textDomainField = $fileHeader->headers[ProjectMetadataScanner::HEADER_TEXT_DOMAIN] ?? null;

        if ($textDomainField !== null && !$textDomainField->isEmpty()) {
            $domain = $textDomainField->value;
        }

        $relativePath = Path::makeRelative($fileHeader->filePath, $source);

        foreach ($fileHeader->headers as $headerName => $headerField) {
            if (!in_array($headerName, self::EXTRACTABLE_HEADERS, true)) {
                continue;
            }

            if ($headerField->isEmpty()) {
                continue;
            }

            $translation = Translation::create(null, $headerField->value);

            if ($fileHeader->isTheme) {
                $translation->getExtractedComments()->add(sprintf('%s of the theme', $headerName));
            } else {
                $translation->getExtractedComments()->add(sprintf('%s of the plugin', $headerName));
            }

            if ($headerField->line > 0) {
                $translation->getReferences()->add($relativePath, $headerField->line);
            } else {
                $translation->getReferences()->add($relativePath);
            }

            $messages[] = new ExtractedMessage($translation, $domain);
        }

        return $messages;
    }
}
