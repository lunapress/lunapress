<?php
declare(strict_types=1);

namespace LunaPress\Foundation\PackageMeta;

use LunaPress\Foundation\Composer\ComposerManager;
use LunaPress\Foundation\ServicePackage\ServicePackageMeta;
use LunaPress\FoundationContracts\Composer\IComposerManager;
use LunaPress\FoundationContracts\PackageMeta\IPackageMetaFactory;
use LunaPress\FoundationContracts\PackageMeta\PackageMeta;
use LunaPress\FoundationContracts\PackageMeta\PackageType;
use Override;
use ReflectionException;

defined('ABSPATH') || exit;

final readonly class PackageMetaFactory implements IPackageMetaFactory
{
    /**
     * @var array<string, callable(string,array): PackageMeta>
     */
    private array $map;
    private IComposerManager $composerManager;

    public function __construct()
    {
        $this->map = [
            PackageType::SERVICE->value => $this->makeServicePackage(...),
        ];

        $this->composerManager = new ComposerManager(self::class);
    }

    /**
     * @inheritDoc
     * @throws ReflectionException
     */
    #[Override]
    public function createAll(): iterable
    {
        $packages = $this->composerManager->getInstalledPackages();
        foreach ($packages as $name => $info) {
            $meta = $this->build($name, $info);
            if ($meta) {
                yield $meta;
            }
        }
    }

    /**
     * @param string $packageName
     * @return PackageMeta|null
     * @throws ReflectionException
     */
    #[Override]
    public function create(string $packageName): ?PackageMeta
    {
        $info = $this->composerManager->getInstalledPackages()[$packageName] ?? null;

        return $info ? $this->build($packageName, $info) : null;
    }

    private function build(string $name, array $info): ?PackageMeta
    {
        $type  = $info['type'] ?? null;
        $maker = $type ? ($this->map[$type] ?? null) : null;

        return $maker ? $maker($name, $info) : null;
    }

    /**
     * @param string $name
     * @param array $info
     * @return PackageMeta|null
     * @throws ReflectionException
     */
    private function makeServicePackage(string $name, array $info): ?PackageMeta
    {
        $baseDir = $this->composerManager->getInstallPath($name);

        if ($baseDir === null) {
            return null;
        }

        $config = $info['extra']['lunapress'] ?? [];

        $diRelative = $config['di'] ?? null;
        if ($diRelative) {
            // remove ./ and possible leading characters /
            $diRelative = preg_replace('#^\.?/+#', '', $diRelative);
            $diAbsolute = realpath($baseDir . DIRECTORY_SEPARATOR . $diRelative);
        } else {
            $diAbsolute = null;
        }

        return new ServicePackageMeta(
            $name,
            $diAbsolute && is_file($diAbsolute) ? $diAbsolute : null,
        );
    }
}
