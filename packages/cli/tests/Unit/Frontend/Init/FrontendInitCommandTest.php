<?php
declare(strict_types=1);

use LunaPress\Cli\Frontend\FrontendFramework;
use LunaPress\Cli\Frontend\Init\FrontendInitCommand;
use LunaPress\Cli\Frontend\Init\FrontendInitConfig;
use LunaPress\Cli\Frontend\Init\IFrontendProjectGenerator;
use LunaPress\Cli\Frontend\PackageManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

beforeEach(function () {
    $this->generator = Mockery::mock(IFrontendProjectGenerator::class);
    $this->generator->shouldReceive('generate')->andReturnNull();

    $this->command = new FrontendInitCommand($this->generator);
    $this->tester  = new CommandTester($this->command);
});

it('sets defaults when no input provided', function () {
    $this->tester->setInputs(['', '', '', '']);
    $code = $this->tester->execute([]);

    expect($code)->toBe(Command::SUCCESS);

    $config = $this->command->getConfig();

    expect($config)->toBeInstanceOf(FrontendInitConfig::class)
        ->and($config->framework)->toBe(FrontendFramework::React)
        ->and($config->useTailwind)->toBeTrue()
        ->and($config->packageManager)->toBe(PackageManager::Pnpm)
        ->and($config->directory)->toBe('frontend');
});

it('sets options from input', function () {
    $this->tester->setInputs(['', 'n', '', 'test']);
    $code = $this->tester->execute([]);

    expect($code)->toBe(Command::SUCCESS);

    $config = $this->command->getConfig();

    expect($config->framework)->toBe(FrontendFramework::React)
        ->and($config->useTailwind)->toBeFalse()
        ->and($config->packageManager)->toBe(PackageManager::Pnpm)
        ->and($config->directory)->toBe('test');
});
