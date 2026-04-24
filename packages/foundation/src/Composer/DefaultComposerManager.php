<?php

declare(strict_types=1);

namespace LunaPress\Foundation\Composer;

use LunaPress\FoundationContracts\Composer\ComposerManager;
use Override;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
use RuntimeException;
use function array_column;
use function file_get_contents;
use function is_array;
use function is_file;
use function is_null;
use function is_object;
use function is_string;
use function json_decode;
use function method_exists;
use function realpath;
use function spl_autoload_functions;
use const DIRECTORY_SEPARATOR;

final class DefaultComposerManager implements ComposerManager
{
    private ?string $loaderPath   = null;
    private ?array $installedJson = null;
    private ?array $installedPhp  = null;

    public function __construct(
        private readonly string $anchorClass
    ) {
    }

    /**
     * @throws ReflectionException
     */
    #[Override]
    public function getCurrentLoaderPath(): string
    {
        if (!is_null($this->loaderPath)) {
            return $this->loaderPath;
        }

        $anchorFile = realpath((new ReflectionClass($this->anchorClass))->getFileName());

        foreach (spl_autoload_functions() ?: [] as $autoload) {
            if (!is_array($autoload)) {
                continue;
            }

            $loader = $autoload[0] ?? null;

            if (!is_object($loader) || !method_exists($loader, 'findFile')) {
                continue;
            }

            $found = $loader->findFile($this->anchorClass);
            if ($found && realpath($found) === $anchorFile) {
                $vendorDir = (new ReflectionProperty($loader, 'vendorDir'))->getValue($loader);
                if (!is_string($vendorDir)) {
                    continue;
                }

                $this->loaderPath = $vendorDir . DIRECTORY_SEPARATOR . 'composer';

                return $this->loaderPath;
            }
        }

        throw new RuntimeException('Cannot locate current Composer loader');
    }

    /**
     * @throws ReflectionException
     */
    #[Override]
    public function getInstalledPackages(): array
    {
        if (!is_null($this->installedJson)) {
            return $this->installedJson;
        }

        $json = $this->getCurrentLoaderPath() . '/installed.json';
        if (!is_file($json)) {
            $this->installedJson = [];
            return $this->installedJson;
        }

        // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
        $data     = json_decode(file_get_contents($json), true);
        $packages = array_column($data['packages'] ?? [], null, 'name');

        $this->installedJson = $packages;

        return $this->installedJson;
    }

    /**
     * @throws ReflectionException
     */
    #[Override]
    public function getInstallPath(string $packageName): ?string
    {
        $installed = $this->installedPhp ??= $this->loadInstalledPhp();
        $versions  = $installed['versions'] ?? [];
        $package   = $versions[$packageName] ?? [];

        if (isset($package['install_path']) && is_string($package['install_path'])) {
            return $package['install_path'];
        }

        return null;
    }

    /**
     * @throws ReflectionException
     */
    private function loadInstalledPhp(): array
    {
        $path = $this->getCurrentLoaderPath() . '/installed.php';
        return is_file($path) ? (require $path) : [];
    }
}
