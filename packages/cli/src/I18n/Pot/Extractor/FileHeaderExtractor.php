<?php
declare(strict_types=1);

namespace LunaPress\Cli\I18n\Pot\Extractor;

use Gettext\Translation;
use LunaPress\Cli\I18n\Constants;
use LunaPress\Cli\I18n\Pot\Extractor\Dto\FileHeader;
use LunaPress\Cli\I18n\Pot\Extractor\Dto\HeaderField;
use Symfony\Component\Filesystem\Path;

final readonly class FileHeaderExtractor implements IExtractor
{
    use ExtractorPatternMatchTrait;

    public const string HEADER_PLUGIN_NAME = 'Plugin Name';
    public const string HEADER_PLUGIN_URI  = 'Plugin URI';
    public const string HEADER_THEME_NAME  = 'Theme Name';
    public const string HEADER_THEME_URI   = 'Theme URI';
    public const string HEADER_DESCRIPTION = 'Description';
    public const string HEADER_AUTHOR      = 'Author';
    public const string HEADER_AUTHOR_URI  = 'Author URI';
    public const string HEADER_VERSION     = 'Version';
    public const string HEADER_LICENSE     = 'License';
    public const string HEADER_DOMAIN_PATH = 'Domain Path';
    public const string HEADER_TEXT_DOMAIN = 'Text Domain';

    public const array COMMON_HEADERS = [
        self::HEADER_DESCRIPTION,
        self::HEADER_AUTHOR,
        self::HEADER_AUTHOR_URI,
        self::HEADER_VERSION,
        self::HEADER_LICENSE,
        self::HEADER_DOMAIN_PATH,
        self::HEADER_TEXT_DOMAIN,
    ];

    public const array PLUGIN_HEADERS = [
        self::HEADER_PLUGIN_NAME,
        self::HEADER_PLUGIN_URI,
        ...self::COMMON_HEADERS,
    ];

    public const array THEME_HEADERS = [
        self::HEADER_THEME_NAME,
        self::HEADER_THEME_URI,
        ...self::COMMON_HEADERS,
    ];

    public const array EXTRACTABLE_HEADERS = [
        self::HEADER_PLUGIN_NAME,
        self::HEADER_PLUGIN_URI,
        self::HEADER_THEME_NAME,
        self::HEADER_THEME_URI,
        self::HEADER_DESCRIPTION,
        self::HEADER_AUTHOR,
        self::HEADER_AUTHOR_URI,
    ];

    public function getPatterns(): array
    {
        return ['*.php', 'style.css'];
    }

    public function extract(array $files, string $source, array $domains = [], array $ignoreDomains = []): array
    {
        /** @var ExtractedMessage[] $messages */
        $messages = [];

        /** @var FileHeader|null $pluginData */
        $pluginData = null;
        /** @var FileHeader|null $themeData */
        $themeData = null;

        sort($files);

        foreach ($files as $file) {
            if ($pluginData !== null && $themeData !== null) {
                break;
            }

            $isPhp = str_ends_with($file, '.php');
            $isCss = str_ends_with($file, '.css');

            if ($isPhp && $pluginData === null) {
                $pluginHeaders = $this->getFileData($file, self::PLUGIN_HEADERS);

                if (isset($pluginHeaders[self::HEADER_PLUGIN_NAME]) && !$pluginHeaders[self::HEADER_PLUGIN_NAME]->isEmpty()) {
                    $pluginData = new FileHeader($file, $pluginHeaders, false);
                }
            }

            if ($isCss && basename($file) === 'style.css' && $themeData === null) {
                $relativePath = Path::makeRelative($file, $source);

                if (!str_starts_with($relativePath, '..') && substr_count($relativePath, '/') <= 1) {
                    $themeHeaders = $this->getFileData($file, self::THEME_HEADERS);
                    if (isset($themeHeaders[self::HEADER_THEME_NAME]) && !$themeHeaders[self::HEADER_THEME_NAME]->isEmpty()) {
                        $themeData = new FileHeader($file, $themeHeaders, true);
                    }
                }
            }
        }

        if ($pluginData !== null) {
            $messages = array_merge($messages, $this->createExtractedMessages($pluginData, $source, $domains, $ignoreDomains));
        }

        if ($themeData !== null) {
            $messages = array_merge($messages, $this->createExtractedMessages($themeData, $source, $domains, $ignoreDomains));
        }

        return $messages;
    }

    /**
     * @param string[] $headers
     * @return array<string, HeaderField>
     */
    private function getFileData(string $file, array $headers): array
    {
        if (!is_readable($file)) {
            return [];
        }

        $fileData = file_get_contents($file, false, null, 0, 8192);

        if ($fileData === false || $fileData === '') {
            return [];
        }

        $fileData = str_replace("\r", "\n", $fileData);

        return $this->getFileDataFromString($fileData, $headers);
    }

    /**
     * @param string[] $headers
     * @return array<string, HeaderField>
     */
    private function getFileDataFromString(string $text, array $headers): array
    {
        /**
         * @var array<string, HeaderField> $result
         */
        $result      = [];
        $headerNames = array_map(fn($h) => preg_quote($h, '/'), $headers);
        $pattern     = '/^[ \t\/*#@]*(' . implode('|', $headerNames) . '):(.*)$/mi';

        preg_match_all($pattern, $text, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);

        $foundHeaders = [];
        foreach ($matches as $match) {
            $field   = $match[1][0];
            $value   = $this->cleanupHeaderComment($match[2][0]);
            $lineNum = substr_count($text, "\n", 0, (int) $match[0][1]) + 1;

            $foundHeaders[$field] = new HeaderField($field, $value, $lineNum);
        }

        foreach ($headers as $field) {
            $result[$field] = $foundHeaders[$field] ?? new HeaderField($field, '', 0);
        }

        return $result;
    }

    private function cleanupHeaderComment(string $text): string
    {
        return trim(preg_replace('/\s*(?:\*\/|\?>).*/', '', $text));
    }

    private function createExtractedMessages(FileHeader $fileHeader, string $source, array $domains, array $ignoreDomains): array
    {
        $messages = [];
        $domain   = Constants::DEFAULT_DOMAIN;

        $textDomainField = $fileHeader->headers[self::HEADER_TEXT_DOMAIN] ?? null;

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
