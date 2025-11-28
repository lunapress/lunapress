<?php
declare(strict_types=1);

use Gettext\Generator\PoGenerator;
use LunaPress\Cli\I18n\Pot\Extractor\PhpStanExtractor;
use LunaPress\Cli\I18n\Pot\Generator\PotGenerator;
use LunaPress\Cli\I18n\Pot\Scanner\PhpStanScanner;
use LunaPress\Test\Package;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

const DEFAULT_DOMAIN = 'default';
const PLUGIN_DOMAIN  = 'bred';
const OTHER_DOMAIN   = 'other';

beforeEach(function () {
    $this->fs        = new Filesystem();
    $this->generator = new PotGenerator(
        [
            new PhpStanExtractor(
                new PhpStanScanner()
            )
        ],
        new PoGenerator(),
        $this->fs
    );
});

it('generates correct pot for scenario', function (string $projectPath) {
    $expectedDir = Path::join($projectPath, 'languages', 'expected');
    $actualDir   = Path::join($projectPath, 'languages', 'actual');

    if ($this->fs->exists($actualDir)) {
        $this->fs->remove($actualDir);
    }
    $this->fs->mkdir($actualDir);

    $this->generator->generate(
        source: $projectPath,
        destinationDir: $actualDir,
    );

    $expectedFinder = (new Finder())->in($expectedDir)->files()->name('*.pot');

    expect($expectedFinder->count())->toBeGreaterThan(0, 'No expected files found in fixture!');

    foreach ($expectedFinder as $expectedFile) {
        $relativePath = $expectedFile->getRelativePathname();
        $actualFile   = $actualDir . '/' . $relativePath;

        expect($actualFile)->toBeFile("File '$relativePath' was not generated!");

        $expectedContent = $expectedFile->getContents();
        $actualContent   = (new SplFileInfo($actualFile, '', ''))->getContents();

        expect(normalizePot($actualContent))->toBe(normalizePot($expectedContent));
    }
})->with(packageFixtureDataset(Package::CLI, 'I18n/Pot/Generator'));

it('filters domains based on onlyDomains and ignoreDomains', function (
    array $onlyDomains,
    array $ignoreDomains,
    array $shouldExist,
    array $shouldNotExist
) {
    $fixturePath = packageFixture(Package::CLI, 'I18n/Pot/Generator/Case01_Default');
    $actualDir   = Path::join($fixturePath, 'languages', 'actual');

    if ($this->fs->exists($actualDir)) {
        $this->fs->remove($actualDir);
    }
    $this->fs->mkdir($actualDir);

    $this->generator->generate(
        source: $fixturePath,
        destinationDir: $actualDir,
        domains: $onlyDomains,
        ignoreDomains: $ignoreDomains
    );

    foreach ($shouldExist as $domain) {
        $file = $actualDir . '/' . $domain . '.pot';
        expect($file)->toBeFile("Expected domain '$domain' to be generated, but file not found.");
    }

    foreach ($shouldNotExist as $domain) {
        $file = $actualDir . '/' . $domain . '.pot';
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

function normalizePot(string $content): string {
    $content = preg_replace('/^"POT-Creation-Date:.*\n/m', '', $content);
    return trim($content);
}
