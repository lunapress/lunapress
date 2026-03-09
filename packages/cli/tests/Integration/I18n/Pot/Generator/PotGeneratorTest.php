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
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

const DEFAULT_DOMAIN = 'default';
const PLUGIN_DOMAIN  = 'bred';
const OTHER_DOMAIN   = 'other';
const FIXTURES_PATH  = 'I18n/Pot/Generator';

beforeEach(function () {
    $this->fs        = new Filesystem();
    $this->generator = new PotGenerator(
        [
            new PhpStanExtractor(
                new PhpStanScanner()
            ),
            new JavascriptExtractor(
                new ProcessFactory(),
                new MapperBuilder()
            )
        ],
        new PoGenerator(),
        $this->fs,
        new ProjectMetadataScanner()
    );
});

it('generates correct pot for scenario', function (string $projectPath) {
    $expectedDir = Path::join($projectPath, 'languages', 'expected');
    $actualDir   = Path::join($projectPath, 'languages', 'actual');

    prepareAllNestedFixtures($projectPath);
    cleanDir($actualDir);

    $this->generator->generate(
        sourceDir: $projectPath,
        destinationDir: $actualDir,
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
) {
    $fixturePath = packageFixture(Package::CLI, Path::join(FIXTURES_PATH, 'Case01_Default'));
    $actualDir   = Path::join($fixturePath, 'languages', 'actual');

    cleanDir($actualDir);

    $this->generator->generate(
        sourceDir: $fixturePath,
        destinationDir: $actualDir,
        domains: $onlyDomains,
        ignoreDomains: $ignoreDomains
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
        'shouldExist' => [DEFAULT_DOMAIN, PLUGIN_DOMAIN, OTHER_DOMAIN],
        'shouldNotExist' => [],
    ],
    'only a few domains' => [
        'onlyDomains' => [DEFAULT_DOMAIN, OTHER_DOMAIN],
        'ignoreDomains' => [],
        'shouldExist' => [DEFAULT_DOMAIN, OTHER_DOMAIN],
        'shouldNotExist' => [PLUGIN_DOMAIN],
    ],
    'ignore several domains' => [
        'onlyDomains' => [],
        'ignoreDomains' => [PLUGIN_DOMAIN, DEFAULT_DOMAIN],
        'shouldExist' => [OTHER_DOMAIN],
        'shouldNotExist' => [PLUGIN_DOMAIN, DEFAULT_DOMAIN],
    ],
    'domains and ignoring domains' => [
        'onlyDomains' => [PLUGIN_DOMAIN, DEFAULT_DOMAIN],
        'ignoreDomains' => [DEFAULT_DOMAIN],
        'shouldExist' => [PLUGIN_DOMAIN],
        'shouldNotExist' => [DEFAULT_DOMAIN, OTHER_DOMAIN],
    ],
]);

it('excludes paths', function () {
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
        include: $includes,
        exclude: $excludes
    );

    $potFile = Path::join($actualDir, DEFAULT_DOMAIN . '.pot');
    expect($potFile)->toBeFile();

    $content = (new SplFileInfo($potFile, '', ''))->getContents();

    expect($content)->toContain('msgid "Plugin"')
        ->not->toContain('msgid "Ignored via folder"')
        ->not->toContain('msgid "Ignored via path"')
        ->not->toContain('msgid "Ignored via wildcard"');
});

it('skips frontend files', function () {
    $fixtureDir = packageFixture(Package::CLI, Path::join(FIXTURES_PATH, 'Case02_Frontend'));
    $actualDir  = Path::join($fixtureDir, 'languages', 'actual');

    prepareAllNestedFixtures($fixtureDir);
    cleanDir($actualDir);

    $this->generator->generate(
        sourceDir: $fixtureDir,
        destinationDir: $actualDir,
        skipFrontend: true
    );

    $potFile = Path::join($actualDir, DEFAULT_DOMAIN . '.pot');
    expect($potFile)->toBeFile();

    $content = $this->fs->readFile($potFile);

    expect($content)->toContain('msgid "PHP string"')
        ->not->toContain('msgid "JS string"');
});

function normalizePot(string $content): string {
    $content = preg_replace('/^"POT-Creation-Date:.*\n/m', '', $content);
    return trim($content);
}
