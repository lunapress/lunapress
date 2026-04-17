<?php
declare(strict_types=1);

namespace LunaPress\Cli\Test\Unit\Build\Archive;

use LunaPress\Cli\Build\Archive\ArchiveCommand;
use LunaPress\Cli\Build\Archive\Exceptions\ArchiveException;
use LunaPress\Cli\Build\Archive\IArchiver;
use LunaPress\Cli\Support\IPathResolver;
use LunaPress\Config\DTO\BuildConfig;
use LunaPress\Config\IConfigResolver;
use LunaPress\Config\ProjectConfig;
use Mockery;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Tester\CommandTester;

beforeEach(function () {
    $this->archiver       = Mockery::mock(IArchiver::class);
    $this->configResolver = Mockery::mock(IConfigResolver::class);
    $this->pathResolver   = Mockery::mock(IPathResolver::class);

    $this->testCommand = new ArchiveCommand($this->archiver, $this->configResolver, $this->pathResolver);
    $this->tester      = new CommandTester($this->testCommand);
});

it('has correct command parameters', function () {
    $definition = $this->testCommand->getDefinition();

    expect($definition->hasArgument('source'))->toBeTrue()
        ->and($definition->getArgument('source')->isRequired())->toBeFalse()

        ->and($definition->hasOption('output'))->toBeTrue()
        ->and($definition->getOption('output')->getShortcut())->toBe('o')
        ->and($definition->getOption('output')->isValueRequired())->toBeTrue();
});

it('executes successfully with valid paths and configs', function (
    ?string $source,
    ?string $output,
    string $sourcePath,
    string $outputPath,
    string $slug,
    ProjectConfig $config
) {
    $this->pathResolver->shouldReceive('projectPath')
        ->once()
        ->with($source)
        ->andReturn($sourcePath);

    if ($output) {
        $this->pathResolver->shouldReceive('projectPath')
            ->once()
            ->with($output)
            ->andReturn($outputPath);
    } else {
        $this->pathResolver->shouldReceive('buildPath')
            ->once()
            ->with("{$slug}.zip")
            ->andReturn($outputPath);
    }

    $this->configResolver->shouldReceive('resolve')
        ->once()
        ->with($sourcePath)
        ->andReturn($config);

    $this->archiver->shouldReceive('archive')
        ->once()
        ->with($sourcePath, $outputPath, $slug, Mockery::type(SymfonyStyle::class));

    $exitCode = $this->tester->execute(array_filter([
        'source'   => $source,
        '--output' => $output,
    ]));

    expect($exitCode)->toBe(Command::SUCCESS)
        ->and($this->tester->getDisplay())->toContain(sprintf('Archive successfully created at: %s', $outputPath));
})->with([
    'default paths and fallback slug' => [
        null,
        null,
        '/var/www/project',
        '/var/www/project/build/project.zip',
        'project',
        ProjectConfig::createDefault(),
    ],
    'explicit input and configured slug' => [
        './src',
        './dist/release.zip',
        '/absolute/path/src',
        '/absolute/path/dist/release.zip',
        'custom-plugin-slug',
        ProjectConfig::createDefault()->withBuild(new BuildConfig(pluginSlug: 'custom-plugin-slug')),
    ]
]);

it('returns failure and displays error', function () {
    $errorMessage = 'Source path does not exist or is not readable.';

    $this->pathResolver->shouldReceive('projectPath')->andReturn('/default/path');
    $this->pathResolver->shouldReceive('buildPath')->andReturn('/default/path.zip');
    $this->configResolver->shouldReceive('resolve')->andReturn(ProjectConfig::createDefault());

    $this->archiver->shouldReceive('archive')
        ->once()
        ->andThrow(new class ($errorMessage) extends ArchiveException {
        });

    $exitCode = $this->tester->execute([]);

    expect($exitCode)->toBe(Command::FAILURE)
        ->and($this->tester->getDisplay())->toContain($errorMessage);
});
