<?php

declare(strict_types=1);

namespace LunaPress\Cli\Build\Archive;

use LunaPress\Cli\Build\Archive\Exceptions\ArchiveException;
use LunaPress\Cli\Support\PathResolver;
use LunaPress\Config\ConfigResolver;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Attribute\Option;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use function basename;
use function sprintf;

#[AsCommand(
    name: 'build:archive',
    description: 'Builds a ZIP with vendor prefix'
)]
final class ArchiveCommand extends Command
{
    public function __construct(
        private readonly Archiver       $archiver,
        private readonly ConfigResolver $configResolver,
        private readonly PathResolver   $pathResolver,
    ) {
        parent::__construct();
    }

    public function __invoke(
        SymfonyStyle $io,

        #[Argument(description: 'Directory to archive')]
        ?string $source = null,

        #[Option(description: 'Output path for the ZIP archive', name: 'output', shortcut: 'o')]
        ?string $output = null,
    ): int {
        $sourcePath = $this->pathResolver->projectPath($source);
        $config = $this->configResolver->resolve($sourcePath);
        $buildConfig = $config->getBuildConfig();

        $slug = $buildConfig?->pluginSlug ?? basename($sourcePath);

        $outputPath = $output
            ? $this->pathResolver->projectPath($output)
            : $this->pathResolver->buildPath(sprintf('%s.zip', $slug));

        try {
            $this->archiver->archive($sourcePath, $outputPath, $slug, $io);
        } catch (ArchiveException $e) {
            $io->error($e->getMessage());

            return Command::FAILURE;
        }

        $io->success(sprintf('Archive successfully created at: %s', $outputPath));

        return Command::SUCCESS;
    }
}
