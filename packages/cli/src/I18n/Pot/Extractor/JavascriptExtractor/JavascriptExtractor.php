<?php
declare(strict_types=1);

namespace LunaPress\Cli\I18n\Pot\Extractor\JavascriptExtractor;

use CuyZ\Valinor\Mapper\MappingError;
use CuyZ\Valinor\MapperBuilder;
use Gettext\Translation;
use LunaPress\Cli\I18n\Pot\Extractor\ExtractedMessage;
use LunaPress\Cli\I18n\Pot\Extractor\ExtractorPatternMatchTrait;
use LunaPress\Cli\I18n\Pot\Extractor\FormatFlagTrait;
use LunaPress\Cli\I18n\Pot\Extractor\IExtractor;
use LunaPress\Cli\I18n\Pot\Extractor\JavascriptExtractor\DTO\CLIOutputItem;
use LunaPress\Cli\Support\IProcessFactory;
use Symfony\Component\Process\Exception\ProcessFailedException;

final readonly class JavascriptExtractor implements IExtractor
{
    use ExtractorPatternMatchTrait;
    use FormatFlagTrait;

    public const string JS_CLI_PACKAGE  = '@lunapress/cli';
    public const string DEFAULT_VERSION = '0.1.6';

    public function __construct(
        private IProcessFactory $processFactory,
        private MapperBuilder $mapperBuilder,
        private string $packageName = self::JS_CLI_PACKAGE,
        private string $version = self::DEFAULT_VERSION
    ) {
    }

    public function extract(array $files, string $source, array $domains = [], array $ignoreDomains = []): array
    {
        $packageConstraint = "{$this->packageName}@{$this->version}";
        $command           = ['npx', '-y', $packageConstraint, 'i18n:makePot', $source, '--json'];

        foreach ($domains as $domain) {
            $command[] = '--domains=' . $domain;
        }

        foreach ($ignoreDomains as $ignoreDomain) {
            $command[] = '--ignoreDomains=' . $ignoreDomain;
        }

        $process = $this->processFactory->create($command);

        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return $this->parseOutput($process->getOutput());
    }

    public function getPatterns(): array
    {
        return ['*.js', '*.jsx', '*.ts', '*.tsx', '*.vue'];
    }

    private function parseOutput(string $json): array
    {
        $raw      = json_decode($json, true);
        $mapper   = $this->mapperBuilder->mapper();
        $messages = [];

        try {
            /** @var CLIOutputItem[] $data */
            $data = $mapper->map(CLIOutputItem::class . '[]', $raw);
        } catch (MappingError $error) {
            return $messages;
        }

        foreach ($data as $item) {
            foreach ($item->files as $chunk) {
                $filePath = $chunk->chunkPath;

                foreach ($chunk->translationEntries as $entry) {
                    $context  = $entry->context ?? null;
                    $original = $entry->text ?? $entry->single ?? null;

                    $translation = Translation::create($context, $original);

                    if (isset($entry->plural)) {
                        $translation->setPlural($entry->plural);
                    }

                    $translation->getReferences()->add($filePath);

                    $message = new ExtractedMessage($translation, $entry->domain);

                    $this->applyFormatFlag($message, 'js-format');

                    $messages[] = $message;
                }
            }
        }

        return $messages;
    }
}
