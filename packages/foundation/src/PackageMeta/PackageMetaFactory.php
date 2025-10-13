<?php
declare(strict_types=1);

namespace LunaPress\Foundation\PackageMeta;

use Composer\InstalledVersions;
use LunaPress\Foundation\ServicePackage\ServicePackageMeta;
use LunaPress\FoundationContracts\PackageMeta\IPackageMetaFactory;
use LunaPress\FoundationContracts\PackageMeta\PackageMeta;
use LunaPress\FoundationContracts\PackageMeta\PackageType;

defined('ABSPATH') || exit;

final readonly class PackageMetaFactory implements IPackageMetaFactory
{
    /**
     * @var array<string, callable(string,array): PackageMeta>
     */
    private array $map;

    public function __construct()
    {
        $this->map = [
            PackageType::SERVICE->value => $this->makeService(...),
        ];
    }

    /** @return iterable<PackageMeta> */
    public function createAll(): iterable
    {
        foreach (InstalledVersions::getAllRawData()[0]['versions'] ?? [] as $name => $info) {
            $meta = $this->build($name, $info);
            if ($meta) {
                yield $meta;
            }
        }
    }

    public function create(string $packageName): ?PackageMeta
    {
        $packages = InstalledVersions::getAllRawData()[0]['versions'] ?? [];
        $info     = $packages[$packageName] ?? null;

        return $info ? $this->build($packageName, $info) : null;
    }

    private function build(string $name, array $info): ?PackageMeta
    {
        $type  = $info['type'] ?? null;
        $maker = $type ? ($this->map[$type] ?? null) : null;

        return $maker ? $maker($name, $info) : null;
    }

    private function makeService(string $name, array $info): ?PackageMeta
    {
        $baseDir = InstalledVersions::getInstallPath($name);

        if ($baseDir === null) {
            return null;
        }

        $config = $info['extra']['lunapress']['config'] ?? [];

        $diRelative = $config['di'] ?? null;
        if ($diRelative) {
            // remove ./ and possible leading characters /
            $diRelative = preg_replace('#^\.?/+#', '', $diRelative);
            $diAbsolute = $baseDir . DIRECTORY_SEPARATOR . $diRelative;
        } else {
            $diAbsolute = null;
        }

        return new ServicePackageMeta(
            $name,
            $diAbsolute && is_file($diAbsolute) ? $diAbsolute : null,
        );
    }
}
