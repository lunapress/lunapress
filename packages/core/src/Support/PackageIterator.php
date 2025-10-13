<?php
declare(strict_types=1);

namespace LunaPress\Core\Support;

use LunaPress\FoundationContracts\Package\IPackage;

defined('ABSPATH') || exit;

trait PackageIterator
{
    /**
     * @param iterable<class-string<IPackage>|IPackage> $packages
     * @param callable(IPackage): void $callback
     */
    protected function iteratePackages(iterable $packages, callable $callback): void
    {
        foreach ($packages as $package) {
            if (is_string($package)) {
                if (!class_exists($package) || !is_a($package, IPackage::class, true)) {
                    continue;
                }
                $package = new $package();
            }

            if ($package instanceof IPackage) {
                $callback($package);
            }
        }
    }
}
