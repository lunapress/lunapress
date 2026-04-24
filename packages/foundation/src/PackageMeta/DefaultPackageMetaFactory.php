<?php

declare(strict_types=1);

namespace LunaPress\Foundation\PackageMeta;

use LunaPress\Foundation\Composer\DefaultComposerManager;
use LunaPress\FoundationContracts\Composer\ComposerManager;
use LunaPress\FoundationContracts\PackageMeta\Exception\PackageMetaException;
use LunaPress\FoundationContracts\PackageMeta\PackageMeta;
use LunaPress\FoundationContracts\PackageMeta\PackageMetaFactory;
use LunaPress\FoundationContracts\PackageMeta\PackageType;
use LunaPress\FoundationContracts\ServicePackage\ServicePackageMeta;
use Override;
use ReflectionException;
use function is_file;
use function preg_replace;
use function realpath;
use const DIRECTORY_SEPARATOR;

final readonly class DefaultPackageMetaFactory implements PackageMetaFactory
{
    /**
     * @var array<string, callable(string,array): PackageMeta>
     */
    private array $builders;
    private ComposerManager $composerManager;

    public function __construct()
    {
        $this->composerManager = new DefaultComposerManager(self::class);
        $this->builders = [
            PackageType::Service->value => $this->makeServicePackage(...),
        ];
    }

    /**
     * @throws ReflectionException|PackageMetaException
     */
    #[Override]
    public function make(string $packageName): PackageMeta
    {
        $info = $this->composerManager->getInstalledPackages()[$packageName] ?? null;
        if ($info === null) {
            throw new PackageMetaException("No composer package found for '{$packageName}'");
        }

        $type = $info['type'] ?? null;

        if ($type === null || !isset($this->builders[$type])) {
            throw new PackageMetaException("Package '{$packageName}' has unsupported or missing type.");
        }

        $builder = $this->builders[$type];

        return $builder($packageName, $info);
    }

    /**
     * @param array<mixed> $info
     * @throws ReflectionException
     */
    private function makeServicePackage(string $name, array $info): ServicePackageMeta
    {
        $baseDir = $this->composerManager->getInstallPath($name);
        $diAbsolute = null;

        if ($baseDir !== null) {
            $config     = $info['extra']['lunapress'] ?? [];
            $diRelative = $config['di'] ?? null;

            if ($diRelative) {
                $diRelative = preg_replace('#^\.?/+#', '', $diRelative);
                $path       = realpath($baseDir . DIRECTORY_SEPARATOR . $diRelative);

                if ($path && is_file($path)) {
                    $diAbsolute = $path;
                }
            }
        }

        return new ServicePackageMeta($name, $diAbsolute);
    }
}
