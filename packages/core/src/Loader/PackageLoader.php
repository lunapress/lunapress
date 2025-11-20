<?php
declare(strict_types=1);

namespace LunaPress\Core\Loader;

use LunaPress\FoundationContracts\Support\ILoader;
use LunaPress\FoundationContracts\Package\IHasPackages;
use LunaPress\FoundationContracts\Package\IPackage;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

defined('ABSPATH') || exit;

final readonly class PackageLoader implements ILoader
{
    public function __construct(
        private IHasPackages $hasPackages,
        private ContainerInterface $container
    ) {
    }

    /**
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function load(): void
    {
        foreach ($this->hasPackages->getPackages() as $packageClass) {
            $package = is_string($packageClass)
                ? $this->container->get($packageClass)
                : $packageClass;

            if ($package instanceof IPackage) {
                (new ModuleLoader($package, $this->container))->load();
            }
        }
    }
}
