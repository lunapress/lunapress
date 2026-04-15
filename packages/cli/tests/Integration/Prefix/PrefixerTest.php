<?php
declare(strict_types=1);

namespace LunaPress\Cli\Test\Integration\Prefix;

use LunaPress\Cli\Prefix\Exceptions\ComposerJsonNotFoundException;
use LunaPress\Cli\Prefix\Exceptions\StraussExecutionException;
use LunaPress\Cli\Prefix\StraussPrefixer;
use LunaPress\Config\ConfigResolver;
use LunaPress\Test\Package;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\Console\Output\BufferedOutput;

beforeEach(function () {
    $this->fs      = new Filesystem();
    $this->tempDir = sys_get_temp_dir() . '/lunapress_prefixer_' . uniqid('', true);

    $this->fs->mkdir($this->tempDir);

    $this->prefixer = new StraussPrefixer();
    $this->configResolver = new ConfigResolver();
    $this->bufferedOutput = new BufferedOutput();
    $this->io = new SymfonyStyle(new ArrayInput([]), $this->bufferedOutput);
});

afterEach(function () {
    if ($this->fs->exists($this->tempDir)) {
        $this->fs->remove($this->tempDir);
    }
});

it('prefixes vendor namespaces according to configuration', function () {
    $fixturePath = packageFixture(Package::CLI, 'Prefix/Case01_Default');
    $this->fs->mirror($fixturePath, $this->tempDir);

    $config = $this->configResolver->resolve($this->tempDir);

    $this->prefixer->prefix($this->tempDir, $config->getStraussConfig(), $this->io);

    $mutatedFile = Path::join($this->tempDir, 'vendor-prefixed', 'some-vendor', 'some-pkg', 'src', 'QueryBuilder.php');

    expect($this->fs->exists($mutatedFile))->toBeTrue();

    $content = $this->fs->readFile($mutatedFile);

    expect($content)
        ->toContain('namespace MyApp\\Vendor\\SomeVendor\\SomePkg;')
        ->not->toContain('namespace SomeVendor\\SomePkg;')
        ->toContain('use PDO;');
});

it('throws exception if composer.json is missing', function () {
    $this->prefixer->prefix($this->tempDir, [], $this->io);
})->throws(ComposerJsonNotFoundException::class);

it('throws exception if composer.json contains invalid json', function () {
    $composerPath = Path::join($this->tempDir, 'composer.json');
    $this->fs->dumpFile($composerPath, '{ "invalid": json }');

    $this->prefixer->prefix($this->tempDir, ['some' => 'config'], $this->io);
})->throws(StraussExecutionException::class, 'Failed to update composer.json');

it('restores composer.json even if strauss execution fails', function () {
    $composerPath = Path::join($this->tempDir, 'composer.json');
    $originalContent = json_encode(['name' => 'test/project'], JSON_PRETTY_PRINT) . "\n";
    $this->fs->dumpFile($composerPath, $originalContent);

    $exceptionThrown = false;
    try {
        $this->prefixer->prefix($this->tempDir, ['namespace_prefix' => 'Fail\\'], $this->io);
    } catch (StraussExecutionException) {
        $exceptionThrown = true;
    }

    expect($exceptionThrown)->toBeTrue()
        ->and($this->fs->readFile($composerPath))->toBe($originalContent);
});

it('captures and reports error output from strauss process', function () {
    $composerPath = Path::join($this->tempDir, 'composer.json');
    $this->fs->dumpFile($composerPath, json_encode(['name' => 'test/project']));

    try {
        $this->prefixer->prefix($this->tempDir, ['namespace_prefix' => 'Fail\\'], $this->io);
    } catch (StraussExecutionException $e) {
        expect($e->getMessage())->toContain('Strauss execution failed');
    }

    expect($this->bufferedOutput->fetch())->not->toBeEmpty();
});
