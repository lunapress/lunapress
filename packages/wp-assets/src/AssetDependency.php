<?php
declare(strict_types=1);

namespace LunaPress\Wp\Assets;

use LunaPress\Wp\AssetsContracts\IAssetDependency;

defined('ABSPATH') || exit;

final class AssetDependency implements IAssetDependency
{
    private string $handle;

    public static function of(string $handle): self
    {
        $instance = new self();
        $instance->handle($handle);

        return $instance;
    }

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
