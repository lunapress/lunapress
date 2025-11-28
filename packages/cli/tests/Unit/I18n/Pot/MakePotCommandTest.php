<?php
declare(strict_types=1);

use LunaPress\Cli\I18n\Pot\MakePotCommand;
use LunaPress\Cli\I18n\Pot\Generator\IPotGenerator;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Filesystem\Path;

beforeEach(function () {
    $this->generator = Mockery::mock(IPotGenerator::class);
    $this->command   = new MakePotCommand($this->generator);
    $this->tester    = new CommandTester($this->command);
});

it('all command parameters', function () {
    $definition = $this->command->getDefinition();

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
    $expected[0] = Path::makeAbsolute($expected[0], getcwd());
    $expected[1] = Path::makeAbsolute($expected[1], getcwd());

    $this->generator->shouldReceive('generate')
        ->once()
        ->with(...$expected);

    $this->tester->execute($input);
})->with([
    'defaults' => [
        [],
        [getcwd(), Path::join(getcwd(), 'languages'), [], [], [], []]
    ],

    'only source' => [
        ['source' => './src'],
        ['./src', Path::join(getcwd(), 'languages'), [], [], [], []]
    ],

    'source and destination' => [
        ['source' => './src', 'destination' => './languages'],
        ['./src', './languages', [], [], [], []]
    ],

    'all' => [
        [
            'source' => './src',
            'destination' => './languages',
            '--domains' => ['plugin'],
            '--ignore-domains' => ['default'],
            '--include' => ['frontend', './plugin.php'],
            '--exclude' => ['vendor', 'foo-*.php', '/frontend/node_modules'],
        ],
        [
            './src',
            './languages',
            ['plugin'],
            ['default'],
            ['frontend', './plugin.php'],
            ['vendor', 'foo-*.php', '/frontend/node_modules']
        ]
    ],
]);

it('outputs success message', function () {
    $this->generator->shouldReceive('generate')->once();
    $this->tester->execute([]);
    expect($this->tester->getDisplay())->toContain('Successfully completed');
});
