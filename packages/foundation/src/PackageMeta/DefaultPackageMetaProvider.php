<?php

declare(strict_types=1);

namespace LunaPress\Foundation\PackageMeta;

use LunaPress\Foundation\Composer\DefaultComposerManager;
use LunaPress\FoundationContracts\Composer\ComposerManager;
use LunaPress\FoundationContracts\PackageMeta\Exception\PackageMetaException;
use LunaPress\FoundationContracts\PackageMeta\PackageMeta;
use LunaPress\FoundationContracts\PackageMeta\PackageMetaFactory;
use LunaPress\FoundationContracts\PackageMeta\PackageMetaProvider;
use ReflectionException;

final readonly class DefaultPackageMetaProvider implements PackageMetaProvider
{
    private ComposerManager $composerManager;

    public function __construct(
        private PackageMetaFactory $factory,
    )
    {
        $this->composerManager = new DefaultComposerManager(self::class);
    }

    /**
     * @return iterable<PackageMeta>
     * @throws ReflectionException
     */
    public function all(): iterable
    {
        foreach ($this->composerManager->getInstalledPackages() as $name => $info) {
            try {
                yield $this->factory->make($name);
            } catch (PackageMetaException) {
                continue;
            }
        }
    }
}
