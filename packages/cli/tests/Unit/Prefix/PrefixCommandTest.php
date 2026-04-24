<?php

declare(strict_types=1);

namespace LunaPress\Cli\Test\Unit\Pefix;

use LunaPress\Cli\Prefix\Exceptions\PrefixException;
use LunaPress\Cli\Prefix\Prefixer;
use LunaPress\Cli\Prefix\PrefixCommand;
use LunaPress\Cli\Support\PathResolver;
use LunaPress\Config\ConfigResolver;
use LunaPress\Config\ProjectConfig;
use Mockery;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use function beforeEach;
use function expect;
use function it;

const VALID_STRAUSS_CONFIG = ['namespace_prefix' => 'Test\\'];

beforeEach(function (): void {
    $this->prefixer = Mockery::mock(Prefixer::class);
    $this->pathResolver = Mockery::mock(PathResolver::class);
    $this->configResolver = Mockery::mock(ConfigResolver::class);

    $this->testCommand = new PrefixCommand($this->prefixer, $this->configResolver, $this->pathResolver);
    $this->tester  = new CommandTester($this->testCommand);

    $this->projectConfig = function (array $strauss): void {
        $projectConfig = ProjectConfig::createDefault()->withStrauss($strauss);

        $this->pathResolver->shouldReceive('projectPath')
            ->andReturn('/any/path');

        $this->configResolver->shouldReceive('resolve')
            ->andReturn($projectConfig);
    };
});

it('executes successfully', function (): void {
    ($this->projectConfig)(VALID_STRAUSS_CONFIG);
    $this->prefixer->shouldReceive('prefix');

    $exitCode = $this->tester->execute([]);

    expect($exitCode)->toBe(Command::SUCCESS)
        ->and($this->tester->getDisplay())->toContain('Successfully completed');
});

it('returns failure when strauss configuration is empty', function (): void {
    ($this->projectConfig)([]);

    $exitCode = $this->tester->execute([]);

    expect($exitCode)->toBe(Command::FAILURE)
        ->and($this->tester->getDisplay())->toContain('Strauss configuration not found');

    $this->prefixer->shouldNotReceive('prefix');
});

it('returns failure and displays error message on PrefixException', function (): void {
    ($this->projectConfig)(VALID_STRAUSS_CONFIG);

    $errorMessage = 'Binary not found or execution failed';
    $this->prefixer->shouldReceive('prefix')
        ->andThrow(new class ($errorMessage) extends PrefixException {
        });

    $exitCode = $this->tester->execute([]);

    expect($exitCode)->toBe(Command::FAILURE)
        ->and($this->tester->getDisplay())->toContain($errorMessage);
});
