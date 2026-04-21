<?php

declare(strict_types=1);

namespace LunaPress\Core\Loader;

use LunaPress\FoundationContracts\Package\IHasPackages;
use LunaPress\FoundationContracts\Package\IPackage;
use LunaPress\FoundationContracts\Support\ILoader;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use function is_string;

final readonly class PackageLoader implements ILoader
{
    public function __construct(
        private IHasPackages $hasPackages,
        private ContainerInterface $container
    ) {
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function load(): void
    {
        foreach ($this->hasPackages->getPackages() as $packageClass) {
            $package = is_string($packageClass)
                ? $this->container->get($packageClass)
                : $packageClass;

            if (!($package instanceof IPackage)) {
				continue;
			}

			(new ModuleLoader($package, $this->container))->load();
        }
    }
}
