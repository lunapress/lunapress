<?php
declare(strict_types=1);

namespace LunaPress\Cli\I18n\Pot\Generator;

use Gettext\Generator\PoGenerator;
use Gettext\Translation;
use Gettext\Translations;
use LunaPress\Cli\I18n\Pot\Extractor\ExtractedMessage;
use LunaPress\Cli\I18n\Pot\Extractor\IExtractor;
use LunaPress\Cli\I18n\Pot\Extractor\JavascriptExtractor\JavascriptExtractor;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\Finder\Finder;

final readonly class PotGenerator implements IPotGenerator
{
    /**
     * @param IExtractor[] $extractors
     */
    public function __construct(
        private array $extractors,
        private PoGenerator $poGenerator,
        private Filesystem $fs
    ) {
    }

    /**
     * @inheritDoc
     */
    public function generate(
        string $sourceDir,
        string $destinationDir,
        array  $domains = [],
        array  $ignoreDomains = [],
        array  $include = [],
        array  $exclude = [],
        bool   $skipFrontend = false,
    ): void {
        $allFiles    = $this->collectFiles($sourceDir, $include, $exclude, $skipFrontend);
        $allMessages = $this->extractMessages($allFiles, $sourceDir, $skipFrontend);
        /** @var array<string, Translations> $allTranslations */
        $allTranslations = [];

        foreach ($allMessages as $message) {
            $domain = $message->getDomain();
            if ($this->shouldSkipDomain($domain, $domains, $ignoreDomains)) {
                continue;
            }

            $collection = $this->getOrCreateCollection($allTranslations, $domain);
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
     * @param string[] $files
     * @return ExtractedMessage[]
     */
    private function extractMessages(array $files, string $source, bool $skipFrontend = false): array
    {
        $messages = [];
        foreach ($this->extractors as $extractor) {
            if ($skipFrontend && $extractor instanceof JavascriptExtractor) {
                continue;
            }

            $batch = array_filter($files, fn($f) => $extractor->supports($f));
            if ($batch) {
                $messages = array_merge($messages, $extractor->extract($batch, $source));
            }
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

    private function getOrCreateCollection(array &$registry, string $domain): Translations
    {
        if (!isset($registry[$domain])) {
            $registry[$domain] = Translations::create($domain);
            $this->setHeaders($registry[$domain], $domain);
        }

        return $registry[$domain];
    }

    /**
     * @param string $source
     * @param string[] $include
     * @param string[] $exclude
     * @param bool $skipFrontend
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

    private function setHeaders(Translations $translations, string $domain): void
    {
        $headers = $translations->getHeaders();

        $headers->set('Project-Id-Version', $domain);
        $headers->set('MIME-Version', '1.0');
        $headers->set('Content-Type', 'text/plain; charset=UTF-8');
        $headers->set('Content-Transfer-Encoding', '8bit');
        $headers->set('POT-Creation-Date', gmdate('Y-m-d\TH:i:s\+00:00'));
        $headers->set('PO-Revision-Date', 'YEAR-MO-DA HO:MI+ZONE');
        $headers->set('X-Generator', 'LunaPress CLI');
    }
}
