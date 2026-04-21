<?php

declare(strict_types=1);

namespace LunaPress\Cli\I18n\Pot\Scanner;

use LunaPress\Cli\I18n\Pot\Extractor\FileHeaderExtractor\Dto\FileHeader;
use LunaPress\Cli\I18n\Pot\Extractor\FileHeaderExtractor\Dto\HeaderField;
use Symfony\Component\Filesystem\Path;
use function array_map;
use function basename;
use function file_get_contents;
use function implode;
use function is_readable;
use function preg_match_all;
use function preg_quote;
use function preg_replace;
use function sort;
use function str_ends_with;
use function str_replace;
use function str_starts_with;
use function substr_count;
use function trim;
use const PREG_OFFSET_CAPTURE;
use const PREG_SET_ORDER;

final class ProjectMetadataScanner
{
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

    /**
     * Scan an array of files to find the main plugin file or theme stylesheet.
     * We don't cache this as it's typically fast and called rarely.
     *
     * @param string[] $files
     */
    public function scan(array $files, string $source): ?FileHeader
    {
        $pluginData = null;
        $themeData  = null;

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

            if (!$isCss || basename($file) !== 'style.css' || $themeData !== null) {
				continue;
			}

			$relativePath = Path::makeRelative($file, $source);

			// Only detect style.css files in the root or an immediate subdirectory of the source.
			if (str_starts_with($relativePath, '..') || substr_count($relativePath, '/') > 1) {
				continue;
			}

			$themeHeaders = $this->getFileData($file, self::THEME_HEADERS);
			if (!isset($themeHeaders[self::HEADER_THEME_NAME]) || $themeHeaders[self::HEADER_THEME_NAME]->isEmpty()) {
				continue;
			}

			$themeData = new FileHeader($file, $themeHeaders, true);
        }

        // Return theme primarily if both found (as per WP CLI behavior generally, but usually only one applies)
        // We'll prefer Theme, then Plugin, or ultimately whatever is found.
        return $themeData ?? $pluginData;
    }

    /**
     * @param string[] $headers
     * @return array<string, HeaderField>
     */
    public function getFileData(string $file, array $headers): array
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
    public function getFileDataFromString(string $text, array $headers): array
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
}
