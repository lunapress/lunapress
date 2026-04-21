<?php

declare(strict_types=1);

namespace LunaPress\Cli\Test\Integration\I18n\Pot\Generator;

use CuyZ\Valinor\MapperBuilder;
use Gettext\Generator\PoGenerator;
use LunaPress\Cli\I18n\Pot\Extractor\JavascriptExtractor\JavascriptExtractor;
use LunaPress\Cli\I18n\Pot\Extractor\PhpStanExtractor;
use LunaPress\Cli\I18n\Pot\Generator\PotGenerator;
use LunaPress\Cli\I18n\Pot\Scanner\PhpStanScanner;
use LunaPress\Cli\I18n\Pot\Scanner\ProjectMetadataScanner;
use LunaPress\Cli\Support\ProcessFactory;
use LunaPress\Test\Package;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use function beforeEach;
use function expect;
use function it;
use function preg_replace;
use function trim;

const DEFAULT_DOMAIN = 'default';
const WC_DOMAIN      = 'woocommerce';
const PLUGIN_DOMAIN  = 'bred';
const OTHER_DOMAIN   = 'other';
const FIXTURES_PATH  = 'I18n/Pot/Generator';

beforeEach(function (): void {
    $this->io        = new SymfonyStyle(new ArrayInput([]), new NullOutput());
    $this->fs        = new Filesystem();
    $this->generator = new PotGenerator(
        [
            new PhpStanExtractor(
                new PhpStanScanner()
            ),
            new JavascriptExtractor(
                new ProcessFactory(),
                new MapperBuilder()
            ),
        ],
        new PoGenerator(),
        $this->fs,
        new ProjectMetadataScanner()
    );
});

it('generates correct pot for scenario', function (string $projectPath): void {
    $expectedDir = Path::join($projectPath, 'languages', 'expected');
    $actualDir   = Path::join($projectPath, 'languages', 'actual');

    prepareAllNestedFixtures($projectPath);
    cleanDir($actualDir);

    $this->generator->generate(
        sourceDir: $projectPath,
        destinationDir: $actualDir,
        io: $this->io
    );

    $expectedFinder = (new Finder())->in($expectedDir)->files()->name('*.pot');

    expect($expectedFinder->count())->toBeGreaterThan(0, 'No expected files found in fixture!');

    foreach ($expectedFinder as $expectedFile) {
        $relativePath = $expectedFile->getRelativePathname();
        $actualFile   = Path::join($actualDir, $relativePath);

        expect($actualFile)->toBeFile("File '$relativePath' was not generated!");

        $expectedContent = $expectedFile->getContents();
        $actualContent   = $this->fs->readFile($actualFile);

        expect(normalizePot($actualContent))->toBe(normalizePot($expectedContent));
    }
})->with(packageFixtureDataset(Package::CLI, FIXTURES_PATH));

it('filters domains based on onlyDomains and ignoreDomains', function (
    array $onlyDomains,
    array $ignoreDomains,
    array $shouldExist,
    array $shouldNotExist
): void {
    $fixturePath = packageFixture(Package::CLI, Path::join(FIXTURES_PATH, 'Case01_Default'));
    $actualDir   = Path::join($fixturePath, 'languages', 'actual');

    cleanDir($actualDir);

    $this->generator->generate(
        sourceDir: $fixturePath,
        destinationDir: $actualDir,
        io: $this->io,
        domains: $onlyDomains,
        ignoreDomains: $ignoreDomains,
    );

    foreach ($shouldExist as $domain) {
        $file = Path::join($actualDir, $domain . '.pot');
        expect($file)->toBeFile("Expected domain '$domain' to be generated, but file not found.");
    }

    foreach ($shouldNotExist as $domain) {
        $file = Path::join($actualDir, $domain . '.pot');
        expect($file)->not->toBeFile("Did not expect domain '$domain' to be generated, but file exists.");
    }
})->with([
    'default: Generate all' => [
        'onlyDomains' => [],
        'ignoreDomains' => [],
        'shouldExist' => [WC_DOMAIN, PLUGIN_DOMAIN, OTHER_DOMAIN],
        'shouldNotExist' => [],
    ],
    'only a few domains' => [
        'onlyDomains' => [WC_DOMAIN, OTHER_DOMAIN],
        'ignoreDomains' => [],
        'shouldExist' => [WC_DOMAIN, OTHER_DOMAIN],
        'shouldNotExist' => [PLUGIN_DOMAIN],
    ],
    'ignore several domains' => [
        'onlyDomains' => [],
        'ignoreDomains' => [PLUGIN_DOMAIN, WC_DOMAIN],
        'shouldExist' => [OTHER_DOMAIN],
        'shouldNotExist' => [PLUGIN_DOMAIN, WC_DOMAIN],
    ],
    'domains and ignoring domains' => [
        'onlyDomains' => [PLUGIN_DOMAIN, WC_DOMAIN],
        'ignoreDomains' => [WC_DOMAIN],
        'shouldExist' => [PLUGIN_DOMAIN],
        'shouldNotExist' => [WC_DOMAIN, OTHER_DOMAIN],
    ],
]);

it('excludes paths', function (): void {
    $fixtureDir = packageFixture(Package::CLI, Path::join(FIXTURES_PATH, 'Case01_Default'));
    $sourceDir  = Path::join($fixtureDir, 'src');
    $actualDir  = Path::join($fixtureDir, 'languages', 'actual');
    $includes   = [
        Path::join($fixtureDir, 'plugin.php'),
        Path::join($fixtureDir, 'templates'),
    ];
    $excludes   = [
        'ExcludedFolder',
        'excluded_file.php',
        '*_temp.php',
    ];

    cleanDir($actualDir);

    $this->generator->generate(
        sourceDir: $sourceDir,
        destinationDir: $actualDir,
        io: $this->io,
        include: $includes,
        exclude: $excludes
    );

    $potFile = Path::join($actualDir, WC_DOMAIN . '.pot');
    expect($potFile)->toBeFile();

    $content = (new SplFileInfo($potFile, '', ''))->getContents();

    expect($content)->toContain('msgid "Plugin"')
        ->not->toContain('msgid "Ignored via folder"')
        ->not->toContain('msgid "Ignored via path"')
        ->not->toContain('msgid "Ignored via wildcard"');
});

it('skips frontend files', function (): void {
    $fixtureDir = packageFixture(Package::CLI, Path::join(FIXTURES_PATH, 'Case02_Frontend'));
    $actualDir  = Path::join($fixtureDir, 'languages', 'actual');

    prepareAllNestedFixtures($fixtureDir);
    cleanDir($actualDir);

    $this->generator->generate(
        sourceDir: $fixtureDir,
        destinationDir: $actualDir,
        io: $this->io,
        skipFrontend: true
    );

    $potFile = Path::join($actualDir, PLUGIN_DOMAIN . '.pot');
    expect($potFile)->toBeFile();

    $content = $this->fs->readFile($potFile);

    expect($content)->toContain('msgid "PHP string"')
        ->not->toContain('msgid "JS string"');
});

it('generates only domain from plugin header if text domain is specified', function (): void {
    $fixtureDir = packageFixture(Package::CLI, Path::join(FIXTURES_PATH, 'Case03_PluginHeader'));
    $actualDir  = Path::join($fixtureDir, 'languages', 'actual');

    cleanDir($actualDir);

    $this->generator->generate(
        sourceDir: $fixtureDir,
        destinationDir: $actualDir,
        io: $this->io,
    );

    $potFile = Path::join($actualDir, PLUGIN_DOMAIN . '.pot');
    expect($potFile)->toBeFile()
        ->and(Path::join($actualDir, DEFAULT_DOMAIN . '.pot'))->not->toBeFile()
        ->and(Path::join($actualDir, WC_DOMAIN . '.pot'))->not->toBeFile();
});

it('shows warning when domains and project headers are missing', function (): void {
    $fixtureDir = packageFixture(Package::CLI, Path::join(FIXTURES_PATH, 'Case01_Default'));
    $actualDir  = Path::join($fixtureDir, 'languages', 'actual');

    cleanDir($actualDir);

    $output = new BufferedOutput();
    $io     = new SymfonyStyle(new ArrayInput([]), $output);

    $this->generator->generate(
        sourceDir: $fixtureDir,
        destinationDir: $actualDir,
        io: $io,
    );

    expect($output->fetch())->toContain('Project headers not found')
        ->and(Path::join($actualDir, WC_DOMAIN . '.pot'))->toBeFile();
});

function normalizePot(string $content): string {
    $content = preg_replace('/^"POT-Creation-Date:.*\n/m', '', $content);
    return trim($content);
}
