<?php

declare(strict_types=1);

namespace LunaPress\Cli\I18n\Pot\Generator;

use Gettext\Generator\PoGenerator;
use Gettext\Translation;
use Gettext\Translations;
use LunaPress\Cli\I18n\Pot\Extractor\ExtractedMessage;
use LunaPress\Cli\I18n\Pot\Extractor\FileHeaderExtractor\Dto\FileHeader;
use LunaPress\Cli\I18n\Pot\Extractor\IExtractor;
use LunaPress\Cli\I18n\Pot\Extractor\JavascriptExtractor\JavascriptExtractor;
use LunaPress\Cli\I18n\Pot\Scanner\ProjectMetadataScanner;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\Finder\Finder;
use function array_diff;
use function array_filter;
use function array_keys;
use function array_map;
use function array_merge;
use function array_push;
use function array_unique;
use function array_values;
use function explode;
use function getcwd;
use function gmdate;
use function in_array;
use function is_dir;
use function is_file;
use function iterator_to_array;
use function preg_grep;
use function sprintf;

final readonly class PotGenerator implements IPotGenerator
{
    /**
     * @param IExtractor[] $extractors
     */
    public function __construct(
        private array $extractors,
        private PoGenerator $poGenerator,
        private Filesystem $fs,
        private ProjectMetadataScanner $metadataScanner,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function generate(
        string $sourceDir,
        string $destinationDir,
        SymfonyStyle $io,
        array  $domains = [],
        array  $ignoreDomains = [],
        array  $include = [],
        array  $exclude = [],
        bool   $skipFrontend = false,
        ?string $cliVersion = null,
    ): void {
        $allFiles        = $this->collectFiles($sourceDir, $include, $exclude, $skipFrontend);
        $projectMetadata = $this->metadataScanner->scan($allFiles, $sourceDir);
        $resolvedDomains = $this->resolveDomains($io, $domains, $projectMetadata, $ignoreDomains);
        $allMessages     = $this->extractMessages($allFiles, $sourceDir, $resolvedDomains, $ignoreDomains, $skipFrontend);
        /** @var array<string, Translations> $allTranslations */
        $allTranslations = [];

        foreach ($allMessages as $message) {
            $domain = $message->getDomain();
            if ($this->shouldSkipDomain($domain, $resolvedDomains, $ignoreDomains)) {
                continue;
            }

            $collection = $this->getOrCreateCollection($allTranslations, $domain, $projectMetadata, $cliVersion);
            $this->processMessage($collection, $message);
        }

        foreach ($allTranslations as $domain => $translations) {
            $filepath = Path::join($destinationDir, $domain . '.pot');
            $content  = $this->poGenerator->generateString($translations);

            $this->fs->dumpFile($filepath, $content);

            echo "Generated: $filepath\n";
        }
    }

    /**
     * @param string[] $cliDomains
     * @param string[] $ignoreDomains
     * @return string[]
     */
    private function resolveDomains(SymfonyStyle $io, array $cliDomains, ?FileHeader $projectMetadata, array &$ignoreDomains): array
    {
        if (!empty($cliDomains)) {
            return $cliDomains;
        }

        if ($projectMetadata !== null) {
            $textDomainField = $projectMetadata->headers[ProjectMetadataScanner::HEADER_TEXT_DOMAIN] ?? null;

            if ($textDomainField !== null && !$textDomainField->isEmpty()) {
                return array_filter(array_map('trim', explode(',', $textDomainField->value)));
            }
        }

        $ignoreDomains[] = 'default';
        $ignoreDomains[] = '';
        $ignoreDomains   = array_unique($ignoreDomains);

        $io->warning('Project headers not found or Text Domain is missing. Extracting all discovered text domains');

        return [];
    }

    /**
     * @param string[] $files
     * @return ExtractedMessage[]
     */
    private function extractMessages(array $files, string $source, array $domains = [], array $ignoreDomains = [], bool $skipFrontend = false): array
    {
        $messages = [];
        foreach ($this->extractors as $extractor) {
            if ($skipFrontend && $extractor instanceof JavascriptExtractor) {
                continue;
            }

            $batch = array_filter($files, fn($f) => $extractor->supports($f));
            if (!$batch) {
				continue;
			}

			$messages = array_merge($messages, $extractor->extract($batch, $source, $domains, $ignoreDomains));
        }
        return $messages;
    }

    private function processMessage(Translations $collection, ExtractedMessage $message): void
    {
        $newTranslation = $message->getTranslation();
        $existing       = $collection->find($newTranslation->getContext(), $newTranslation->getOriginal());

        if ($existing instanceof Translation) {
            $merged = $existing->mergeWith($newTranslation);
            $collection->add($merged);
        } else {
            $collection->add($newTranslation);
        }
    }

    private function getOrCreateCollection(array &$registry, string $domain, ?FileHeader $metadata, ?string $cliVersion): Translations
    {
        if (!isset($registry[$domain])) {
            $registry[$domain] = Translations::create($domain);
            $this->setHeaders($registry[$domain], $domain, $metadata, $cliVersion);
        }

        return $registry[$domain];
    }

    /**
     * @param string[] $include
     * @param string[] $exclude
     * @return string[]
     */
    private function collectFiles(
        string $source,
        array $include = [],
        array $exclude = [],
        bool $skipFrontend = false,
    ): array {
        $finder = new Finder();
        $finder
            ->files()
            ->ignoreVCSIgnored(true)
            ->sortByName();

        $patternsToScan = [];
        foreach ($this->extractors as $extractor) {
            if ($skipFrontend && $extractor instanceof JavascriptExtractor) {
                continue;
            }
            array_push($patternsToScan, ...$extractor->getPatterns());
        }

        if (!empty($patternsToScan)) {
            $finder->name(array_unique($patternsToScan));
        }

        $dirsToScan = [$source];
        foreach ($include as $path) {
            $absPath = Path::makeAbsolute($path, getcwd());

            if (is_dir($absPath)) {
                $dirsToScan[] = $absPath;
            } elseif (is_file($absPath)) {
                $finder->append([$absPath]);
            }
        }
        $finder->in(array_values(array_unique($dirsToScan)));

        $patterns     = preg_grep('/[*?]/', $exclude);
        $excludePaths = array_diff($exclude, $patterns);

        if (!empty($patterns)) {
            $finder->notName($patterns);
        }

        if (!empty($excludePaths)) {
            $finder->notPath($excludePaths);
        }

        return array_keys(iterator_to_array($finder));
    }

    private function shouldSkipDomain(string $domain, array $only, array $ignore): bool
    {
        if (in_array($domain, $ignore, true)) {
            return true;
        }
        if (!empty($only) && !in_array($domain, $only, true)) {
            return true;
        }
        return false;
    }

    private function setHeaders(Translations $translations, string $domain, ?FileHeader $metadata, ?string $cliVersion): void
    {
        $headers = $translations->getHeaders();

        $headers->set('MIME-Version', '1.0');
        $headers->set('Content-Type', 'text/plain; charset=UTF-8');
        $headers->set('Content-Transfer-Encoding', '8bit');
        $headers->set('POT-Creation-Date', gmdate('Y-m-d\TH:i:s\+00:00'));
        $headers->set('PO-Revision-Date', 'YEAR-MO-DA HO:MI+ZONE');

        $generatorHeader = 'LunaPress CLI';
        if ($cliVersion) {
            $generatorHeader .= ' ' . $cliVersion;
        }
        $headers->set('X-Generator', $generatorHeader);

        $name    = $domain;
        $version = null;
        $slug    = $domain;
        $bugUrl  = null;
        $author  = null;
        $license = null;

        if ($metadata !== null) {
            $version = $metadata->headers[ProjectMetadataScanner::HEADER_VERSION]?->value;
            $author  = $metadata->headers[ProjectMetadataScanner::HEADER_AUTHOR]?->value;
            $license = $metadata->headers[ProjectMetadataScanner::HEADER_LICENSE]?->value;

            if ($metadata->isTheme) {
                $name   = $metadata->headers[ProjectMetadataScanner::HEADER_THEME_NAME]?->value ?: $name;
                $bugUrl = sprintf('https://wordpress.org/support/theme/%s', $slug);
            } else {
                $name   = $metadata->headers[ProjectMetadataScanner::HEADER_PLUGIN_NAME]?->value ?: $name;
                $bugUrl = sprintf('https://wordpress.org/support/plugin/%s', $slug);
            }
        }

        $headers->set('Project-Id-Version', $name . ($version ? ' ' . $version : ''));

        if ($bugUrl !== null) {
            $headers->set('Report-Msgid-Bugs-To', $bugUrl);
        }

        $headers->set('Last-Translator', 'FULL NAME <EMAIL@ADDRESS>');
        $headers->set('Language-Team', 'LANGUAGE <LL@li.org>');

        if ($metadata === null || !$author) {
			return;
		}

		$year = gmdate('Y');
		$type = $metadata->isTheme ? 'theme' : 'plugin';

		if ($license) {
			$description = sprintf("Copyright (C) %s %s\nThis file is distributed under the %s.", $year, $author, $license);
		} else {
			$description = sprintf("Copyright (C) %s %s\nThis file is distributed under the same license as the %s %s.", $year, $author, $name, $type);
		}

		$translations->setDescription($description);
    }
}
