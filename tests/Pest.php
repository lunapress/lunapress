<?php
declare(strict_types=1);

use LunaPress\Test\Package;
use Pest\TestSuite;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

require __DIR__ . '/../packages/cli/tests/Pest.php';

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

function packageFixture(Package $package, string $fixturePath): string
{
    return Path::join(
        TestSuite::getInstance()->rootPath,
        'packages',
        $package->value,
        'tests',
        'Fixture',
        str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $fixturePath),
    );
}

function packagePath(Package $package)
{
    return Path::join(
        TestSuite::getInstance()->rootPath,
        'packages',
        $package->value,
    );
}

/**
 * @param Package $package
 * @param string $fixturePath
 * @return array
 */
function packageFixtureDataset(Package $package, string $fixturePath): array
{
    $fixturesDir = packageFixture($package, $fixturePath);

    $finder = (new Finder())->in($fixturesDir)->depth(0)->directories();

    $dataset = [];
    foreach ($finder as $directory) {
        $dataset[$directory->getFilename()] = [$directory->getRealPath()];
    }

    return $dataset;
}

function cleanDir(string $path): void
{
    $fs = new Filesystem();
    if ($fs->exists($path)) {
        $fs->remove($path);
    }
    $fs->mkdir($path);
}

function prepareNodeFixture(string $path): void
{
    $install = new Process(['pnpm', 'install'], $path, ['CI' => 'true']);
    $install->setTimeout(600);
    $install->run();

    if (!$install->isSuccessful()) {
        throw new ProcessFailedException($install);
    }

    $packageJson = json_decode(file_get_contents($path . '/package.json'), true);

    if (isset($packageJson['scripts']['build'])) {
        $build = new Process(['pnpm', 'run', 'build'], $path);
        $build->setTimeout(600);
        $build->run();

        if (!$build->isSuccessful()) {
            throw new ProcessFailedException($build);
        }
    }
}

function prepareAllNestedFixtures(string $rootPath): void
{
    $finder = new Finder();
    $finder->files()
        ->in($rootPath)
        ->name('package.json')
        ->exclude('node_modules');

    foreach ($finder as $file) {
        $configDir = $file->getPath();
        prepareNodeFixture($configDir);
    }
}
