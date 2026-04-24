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
use function dirname;
use function is_writable;
use function preg_match;
use function str_ends_with;
use function str_replace;

final readonly class ZipArchiver implements Archiver
{
    public function __construct(
        private Filesystem $fs = new Filesystem()
    ) {
    }

    public function archive(string $absoluteSourcePath, string $absoluteOutputPath, string $baseDirectory, SymfonyStyle $io): void
    {
        if (!$this->fs->exists($absoluteSourcePath)) {
            throw SourcePathNotFoundException::forPath($absoluteSourcePath);
        }

        $outputDir = dirname($absoluteOutputPath);

        if (!$this->fs->exists($outputDir)) {
            $this->fs->mkdir($outputDir);
        }

        if (!is_writable($outputDir) || ($this->fs->exists($absoluteOutputPath) && !is_writable($absoluteOutputPath))) {
            throw OutputPathNotWritableException::forPath($absoluteOutputPath);
        }

        $zip = new ZipArchive();
        $status = $zip->open($absoluteOutputPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        if ($status !== true) {
            throw ZipOperationException::fromFailure('open', $status);
        }

        $finder = new Finder();
        $finder->in($absoluteSourcePath)
            ->ignoreVCS(true)
            ->ignoreDotFiles(false)
            ->files();

        $this->applyInternalExclusions($finder, $absoluteSourcePath, $absoluteOutputPath);
        $this->applyIgnoreRules($finder, $absoluteSourcePath, $io);

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

    private function applyInternalExclusions(Finder $finder, string $sourcePath, string $outputPath): void
    {
        $outputDir = dirname($outputPath);

        $finder->filter(static function (SplFileInfo $file) use ($sourcePath, $outputPath, $outputDir): bool {
            $realPath = $file->getRealPath();

            if ($realPath === false || $realPath === $outputPath) {
                return false;
            }

            if ($outputDir !== $sourcePath && Path::isBasePath($sourcePath, $outputDir)) {
                if (Path::isBasePath($outputDir, $realPath)) {
                    return false;
                }
            }

            return true;
        });
    }
}
