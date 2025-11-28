<?php
declare(strict_types=1);

namespace LunaPress\Cli\I18n\Pot\Generator;

use Gettext\Generator\PoGenerator;
use Gettext\Translation;
use Gettext\Translations;
use LunaPress\Cli\I18n\Pot\Extractor\ExtractedMessage;
use LunaPress\Cli\I18n\Pot\Extractor\IExtractor;
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
    public function generate(string $source, string $destinationDir, array $domains = [], array $ignoreDomains = []): void
    {
        $allFiles    = $this->collectFiles($source);
        $allMessages = $this->extractMessages($allFiles, $source);
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
    private function extractMessages(array $files, string $source): array
    {
        $messages = [];
        foreach ($this->extractors as $extractor) {
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
     * @return string[]
     */
    private function collectFiles(string $source): array
    {
        $finder = new Finder();
        $finder->in($source)->files();

        $files = [];
        foreach ($finder as $file) {
            $files[] = $file->getPathname();
        }
        return $files;
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
