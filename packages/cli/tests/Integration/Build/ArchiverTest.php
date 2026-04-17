<?php
declare(strict_types=1);

namespace LunaPress\Cli\Test\Integration\Build;

use LunaPress\Cli\Build\Archive\Exceptions\ArchiveException;
use LunaPress\Cli\Build\Archive\ZipArchiver;
use LunaPress\Test\Package;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use ZipArchive;

beforeEach(function () {
    $this->fixturePath = Path::join('Build', 'Archive');
    $this->fs = new Filesystem();
    $this->tempDir = sys_get_temp_dir() . '/lunapress_archiver_' . uniqid('', true);
    $this->sourcePath = Path::join($this->tempDir, 'source');
    $this->outputPath = Path::join($this->tempDir, 'output.zip');
    $this->baseDirectory = 'my-plugin-slug';

    $this->fs->mkdir($this->sourcePath);

    $this->archiver = new ZipArchiver();
    $this->bufferedOutput = new BufferedOutput();
    $this->io = new SymfonyStyle(new ArrayInput([]), $this->bufferedOutput);
});

afterEach(function () {
    if ($this->fs->exists($this->tempDir)) {
        $this->fs->remove($this->tempDir);
    }
});

it('creates a valid archive wrapping files inside a base directory', function () {
    $fixturePath = packageFixture(Package::CLI, Path::join($this->fixturePath, 'Case01_Default'));
    $this->fs->mirror($fixturePath, $this->sourcePath);

    $this->archiver->archive($this->sourcePath, $this->outputPath, $this->baseDirectory, $this->io);

    expect($this->fs->exists($this->outputPath))->toBeTrue();

    $zip = new ZipArchive();
    $status = $zip->open($this->outputPath, ZipArchive::CHECKCONS);

    expect($status)->toBeTrue()
        ->and($zip->getFromName("{$this->baseDirectory}/plugin.php"))->not->toBeFalse()
        ->and($zip->getFromName("{$this->baseDirectory}/includes/functions.php"))->not->toBeFalse();

    $zip->close();
});

it('respects .distignore including negations and excludes vcs', function () {
    $fixturePath = packageFixture(Package::CLI, Path::join($this->fixturePath, 'Case02_WithDistignore'));
    $this->fs->mirror($fixturePath, $this->sourcePath);

    $this->archiver->archive($this->sourcePath, $this->outputPath, $this->baseDirectory, $this->io);

    $zip = new ZipArchive();
    $zip->open($this->outputPath);

    expect($zip->getFromName("{$this->baseDirectory}/plugin.php"))->not->toBeFalse()
        ->and($zip->getFromName("{$this->baseDirectory}/vendor/autoload.php"))->not->toBeFalse()
        ->and($zip->getFromName("{$this->baseDirectory}/.github/FUNDING.yml"))->not->toBeFalse()
        ->and($zip->getFromName("{$this->baseDirectory}/tests/PluginTest.php"))->toBeFalse()
        ->and($zip->getFromName("{$this->baseDirectory}/README.md"))->toBeFalse()
        ->and($zip->getFromName("{$this->baseDirectory}/.env"))->toBeFalse()
        ->and($zip->getFromName("{$this->baseDirectory}/.github/workflows/test.yml"))->toBeFalse()
        ->and($zip->getFromName("{$this->baseDirectory}/.distignore"))->toBeFalse()
        ->and($zip->getFromName("{$this->baseDirectory}/.git/config"))->toBeFalse();

    $zip->close();
});

it('emits a warning when .distignore is missing', function () {
    $fixturePath = packageFixture(Package::CLI, Path::join($this->fixturePath, 'Case01_Default'));
    $this->fs->mirror($fixturePath, $this->sourcePath);

    $this->archiver->archive($this->sourcePath, $this->outputPath, $this->baseDirectory, $this->io);

    $output = $this->bufferedOutput->fetch();

    expect($output)->toContain('No .distignore file found');
});

it('throws exception if source path does not exist', function () {
    $missingSource = Path::join($this->tempDir, 'missing-source');

    $this->archiver->archive($missingSource, $this->outputPath, $this->baseDirectory, $this->io);
})->throws(ArchiveException::class);

it('throws exception if output path is not writable', function () {
    $this->fs->mkdir($this->outputPath);

    $this->fs->dumpFile(Path::join($this->sourcePath, 'test.txt'), 'test');

    $this->archiver->archive($this->sourcePath, $this->outputPath, $this->baseDirectory, $this->io);
})->throws(ArchiveException::class);
