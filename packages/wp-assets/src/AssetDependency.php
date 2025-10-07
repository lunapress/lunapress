<?php
declare(strict_types=1);

namespace LunaPress\Wp\Assets;

use LunaPress\Wp\AssetsContracts\IAssetDependency;

defined('ABSPATH') || exit;


final readonly class AssetDependency implements IAssetDependency
{
    private string $handle;

    public function handle(string $handle): self
    {
        $this->handle = $handle;

        return $this;
    }

    public function getHandle(): string
    {
        return $this->handle;
    }
}
