<?php
declare(strict_types=1);

namespace LunaPress\Cli\Test\Unit\I18n\Pot;

use LunaPress\Cli\I18n\Pot\Generator\IPotGenerator;
use LunaPress\Cli\I18n\Pot\MakePotCommand;
use LunaPress\Cli\Support\IPathResolver;
use Mockery;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Filesystem\Path;

beforeEach(function () {
    $this->generator    = Mockery::mock(IPotGenerator::class);
    $this->pathResolver = Mockery::mock(IPathResolver::class);
    $this->testCommand      = new MakePotCommand($this->generator, $this->pathResolver);
    $this->tester       = new CommandTester($this->testCommand);
});

it('all command parameters', function () {
    $definition = $this->testCommand->getDefinition();

    expect($definition->hasArgument('source'))->toBeTrue()
        ->and($definition->getArgument('source')->isRequired())->toBeFalse()

        ->and($definition->hasArgument('destination'))->toBeTrue()
        ->and($definition->getArgument('destination')->isRequired())->toBeFalse()

        ->and($definition->hasOption('domains'))->toBeTrue()
        ->and($definition->getOption('domains')->isArray())->toBeTrue()
        ->and($definition->getOption('domains')->isValueRequired())->toBeTrue()

        ->and($definition->hasOption('ignore-domains'))->toBeTrue()
        ->and($definition->getOption('ignore-domains')->isArray())->toBeTrue()
        ->and($definition->getOption('ignore-domains')->isValueRequired())->toBeTrue()

        ->and($definition->hasOption('exclude'))->toBeTrue()
        ->and($definition->getOption('exclude')->isArray())->toBeTrue()
        ->and($definition->getOption('exclude')->isValueRequired())->toBeTrue()

        ->and($definition->hasOption('include'))->toBeTrue()
        ->and($definition->getOption('include')->isArray())->toBeTrue()
        ->and($definition->getOption('include')->isValueRequired())->toBeTrue();
});

it('correctly passes arguments combination', function ($input, $expected) {
    $sourcePath      = Path::makeAbsolute($expected[0], getcwd());
    $destinationPath = Path::makeAbsolute($expected[1], getcwd());

    $this->pathResolver->shouldReceive('projectPath')
        ->once()
        ->with($input['source'] ?? null)
        ->andReturn($sourcePath);

    $this->pathResolver->shouldReceive('languages')
        ->once()
        ->with($input['destination'] ?? null)
        ->andReturn($destinationPath);

    $generatorArgs    = $expected;
    $generatorArgs[0] = $sourcePath;
    $generatorArgs[1] = $destinationPath;

    array_splice($generatorArgs, 2, 0, [Mockery::type(SymfonyStyle::class)]);

    $this->generator->shouldReceive('generate')
        ->once()
        ->with(...$generatorArgs);

    $this->tester->execute($input);
})->with([
    'defaults' => [
        [],
        [getcwd(), Path::join(getcwd(), 'languages'), [], [], [], [], false, null]
    ],

    'only source' => [
        ['source' => './src'],
        ['./src', Path::join(getcwd(), 'languages'), [], [], [], [], false, null]
    ],

    'source and destination' => [
        ['source' => './src', 'destination' => './languages'],
        ['./src', './languages', [], [], [], [], false, null]
    ],

    'all' => [
        [
            'source' => './src',
            'destination' => './languages',
            '--domains' => ['plugin'],
            '--ignore-domains' => ['default'],
            '--include' => ['frontend', './plugin.php'],
            '--exclude' => ['vendor', 'foo-*.php', '/frontend/node_modules'],
            '--skip-frontend' => true,
        ],
        [
            './src',
            './languages',
            ['plugin'],
            ['default'],
            ['frontend', './plugin.php'],
            ['vendor', 'foo-*.php', '/frontend/node_modules'],
            true,
            null
        ]
    ],
]);

it('outputs success message', function () {
    $this->pathResolver->shouldReceive('projectPath')->andReturn(getcwd());
    $this->pathResolver->shouldReceive('languages')->andReturn(Path::join(getcwd(), 'languages'));
    $this->generator->shouldReceive('generate')->once();

    $this->tester->execute([]);

    expect($this->tester->getDisplay())->toContain('Successfully completed');
});
