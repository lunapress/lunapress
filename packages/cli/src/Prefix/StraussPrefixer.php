<?php
declare(strict_types=1);

namespace LunaPress\Cli\Prefix;

use Composer\InstalledVersions;
use JsonException;
use LunaPress\Cli\Prefix\Exceptions\ComposerJsonNotFoundException;
use LunaPress\Cli\Prefix\Exceptions\StraussBinaryMissingException;
use LunaPress\Cli\Prefix\Exceptions\StraussExecutionException;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\Process\Process;

final class StraussPrefixer implements IPrefixer
{
    public function __construct(
        private Filesystem $fs = new Filesystem()
    ) {
    }

    /**
     * @param string $targetPath
     * @param array $config
     * @param SymfonyStyle $io
     * @return void
     * @throws JsonException
     */
    public function prefix(string $targetPath, array $config, SymfonyStyle $io): void
    {
        $composerPath = Path::join($targetPath, 'composer.json');

        if (!$this->fs->exists($composerPath)) {
            throw new ComposerJsonNotFoundException(
                sprintf('composer.json not found in target directory: %s', $targetPath)
            );
        }

        $originalComposer = $this->fs->readFile($composerPath);

        try {
            $this->injectConfig($composerPath, $originalComposer, $config);

            $this->executeProcess($targetPath, $io);
        } catch (JsonException $e) {
            throw new StraussExecutionException('Failed to update composer.json: ' . $e->getMessage(), 0, $e);
        } finally {
            $this->fs->dumpFile($composerPath, $originalComposer);
        }
    }

    /**
     * @param string $path
     * @param string $content
     * @param array $config
     * @return void
     * @throws JsonException
     */
    private function injectConfig(string $path, string $content, array $config): void
    {
        $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        $data['extra']['strauss'] = $config;

        $this->fs->dumpFile(
            $path,
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n"
        );
    }

    /**
     * @param string $workingDir
     * @param SymfonyStyle $io
     * @return void
     */
    private function executeProcess(string $workingDir, SymfonyStyle $io): void
    {
        $pharPath = Path::join(
            InstalledVersions::getInstallPath('brianhenryie/strauss-phar'),
            'strauss.phar'
        );

        if (!$this->fs->exists($pharPath)) {
            throw new StraussBinaryMissingException('Strauss binary not found. Package might be corrupted.');
        }

        $process = new Process([PHP_BINARY, $pharPath], $workingDir);
        $process->setTimeout(null);

        $process->run(function (string $type, string $buffer) use ($io): void {
            if ($type === Process::ERR) {
                $io->text('<error>' . trim($buffer) . '</error>');
            } else {
                $io->text('<info>' . trim($buffer) . '</info>');
            }
        });

        if (!$process->isSuccessful()) {
            $errorOutput = $process->getErrorOutput() ?: $process->getOutput();

            throw new StraussExecutionException(
                sprintf("Strauss execution failed with code %d.\nOutput: %s", $process->getExitCode(), trim($errorOutput))
            );
        }
    }
}
