<?php
declare(strict_types=1);

namespace LunaPress\Cli\Build\Archive;

use LunaPress\Cli\Build\Archive\Exceptions\OutputPathNotWritableException;
use LunaPress\Cli\Build\Archive\Exceptions\SourcePathNotFoundException;
use LunaPress\Cli\Build\Archive\Exceptions\ZipOperationException;
use SplFileInfo;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\Gitignore;
use Throwable;
use ZipArchive;

final readonly class ZipArchiver implements IArchiver
{
    public function __construct(
        private Filesystem $fs = new Filesystem()
    ) {
    }

    public function archive(string $sourcePath, string $outputPath, string $baseDirectory, SymfonyStyle $io): void
    {
        if (!$this->fs->exists($sourcePath)) {
            throw SourcePathNotFoundException::forPath($sourcePath);
        }

        $outputDir = dirname($outputPath);

        if (!$this->fs->exists($outputDir)) {
            $this->fs->mkdir($outputDir);
        }

        if (!is_writable($outputDir) || ($this->fs->exists($outputPath) && !is_writable($outputPath))) {
            throw OutputPathNotWritableException::forPath($outputPath);
        }

        $zip = new ZipArchive();
        $status = $zip->open($outputPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        if ($status !== true) {
            throw ZipOperationException::fromFailure('open', $status);
        }

        $finder = new Finder();
        $finder->in($sourcePath)
            ->ignoreVCS(true)
            ->ignoreDotFiles(false)
            ->files();

        $this->applyIgnoreRules($finder, $sourcePath, $io);

        if (!$finder->hasResults()) {
            $zip->close();
            return;
        }

        $io->progressStart($finder->count());

        try {
            foreach ($finder as $file) {
                $localPath = Path::join($baseDirectory, $file->getRelativePathname());

                if (!$zip->addFile($file->getRealPath(), $localPath)) {
                    throw ZipOperationException::fromFailure('addFile', $file->getRealPath());
                }

                $io->progressAdvance();
            }
        } catch (Throwable $exception) {
            $zip->close();
            throw ZipOperationException::fromFailure('build', 'Exception during file iteration', $exception);
        }

        $io->progressFinish();

        if (!$zip->close()) {
            throw ZipOperationException::fromFailure('close', 'Failed to finalize archive');
        }
    }

    private function applyIgnoreRules(Finder $finder, string $sourcePath, SymfonyStyle $io): void
    {
        $distignorePath = Path::join($sourcePath, '.distignore');

        if (!$this->fs->exists($distignorePath)) {
            $io->warning('No .distignore file found. All files (except VCS) will be included in the archive.');
            return;
        }

        $content = $this->fs->readFile($distignorePath);

        $exclusionRegex = Gitignore::toRegex($content);
        $inclusionRegex = Gitignore::toRegexMatchingNegatedPatterns($content);

        $finder->filter(function (SplFileInfo $file) use ($exclusionRegex, $inclusionRegex): bool {
            $relativePath = str_replace('\\', '/', $file->getRelativePathname());

            if ($file->isDir() && !str_ends_with($relativePath, '/')) {
                $relativePath .= '/';
            }

            $isIgnored = false;

            if (preg_match($exclusionRegex, $relativePath)) {
                $isIgnored = true;
            }

            if (preg_match($inclusionRegex, $relativePath)) {
                $isIgnored = false;
            }

            return !$isIgnored;
        });
    }
}
