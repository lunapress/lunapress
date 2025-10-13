<?php
declare(strict_types=1);

namespace LunaPress\Foundation\PackageMeta;

use Composer\InstalledVersions;
use LunaPress\Foundation\ServicePackage\ServicePackageMeta;
use LunaPress\FoundationContracts\PackageMeta\IPackageMetaFactory;
use LunaPress\FoundationContracts\PackageMeta\PackageMeta;
use LunaPress\FoundationContracts\PackageMeta\PackageType;
use Override;
use ReflectionClass;
use ReflectionException;

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
            PackageType::SERVICE->value => $this->makeServicePackage(...),
        ];
    }

    /**
     * @inheritDoc
     * @throws ReflectionException
     */
    #[Override]
    public function createAll(array $autoLoaders = []): iterable
    {
        foreach ($autoLoaders as $loaderClass) {
            $ref  = new ReflectionClass($loaderClass);
            $json = dirname($ref->getFileName(), 2) . '/composer/installed.json';
            if (!is_file($json)) {
                continue;
            }

            // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
            $data     = json_decode(file_get_contents($json), true);
            $packages = array_column($data['packages'] ?? [], null, 'name');

            foreach ($packages as $name => $info) {
                $meta = $this->build($name, $info);
                if ($meta) {
                    yield $meta;
                }
            }
        }
    }

    #[Override]
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

    private function makeServicePackage(string $name, array $info): ?PackageMeta
    {
        $baseDir = InstalledVersions::getInstallPath($name);

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
