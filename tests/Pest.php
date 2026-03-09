<?php
declare(strict_types=1);

use LunaPress\Test\Package;
use Pest\TestSuite;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

require __DIR__ . '/../packages/cli/tests/Pest.php';

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function packageFixture(Package $package, string $fixturePath): string
{
    $path = implode(DIRECTORY_SEPARATOR, [
        TestSuite::getInstance()->rootPath,
        'packages',
        $package->value,
        'tests',
        'Fixture',
        str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $fixturePath),
    ]);

    $realPath = realpath($path);

    if ($realPath === false) {
        throw new InvalidArgumentException(
            'The fixture file [' . $path . '] does not exist.',
        );
    }

    return $realPath;
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
