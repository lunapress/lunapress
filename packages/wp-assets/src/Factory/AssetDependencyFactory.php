<?php
declare(strict_types=1);

namespace LunaPress\Wp\Assets\Factory;

use LunaPress\Wp\AssetsContracts\Entity\IAssetDependency;
use LunaPress\Wp\AssetsContracts\Factory\IAssetDependencyFactory;
use Psr\Container\ContainerInterface;

defined('ABSPATH') || exit;

final class AssetDependencyFactory implements IAssetDependencyFactory
{
    public function __construct(
        private ContainerInterface $container,
    ) {
    }

    public function make(string $handle): IAssetDependency
    {
        /** @var IAssetDependency $dep */
        $dep = $this->container->get(IAssetDependency::class);

        return $dep
            ->handle($handle);
    }
}
