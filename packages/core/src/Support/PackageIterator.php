<?php

declare(strict_types=1);

namespace LunaPress\Core\Support;

use LunaPress\FoundationContracts\Package\IPackage;
use function class_exists;
use function is_a;
use function is_string;

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

            if (!($package instanceof IPackage)) {
				continue;
			}

			$callback($package);
        }
    }
}
