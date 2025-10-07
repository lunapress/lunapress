<?php
declare(strict_types=1);

namespace LunaPress\Wp\Assets\WpEnqueueStyle;

use LunaPress\Wp\AssetsContracts\IAssetDependency;
use LunaPress\Wp\AssetsContracts\WpEnqueueStyle\IWpEnqueueStyleFunction;

defined('ABSPATH') || exit;

final class WpEnqueueStyle implements IWpEnqueueStyleFunction
{
    private string $handle;
    private string $src = '';

    /** @var IAssetDependency[] */
    private array $deps               = [];
    private string|bool|null $version = false;
    private string $media             = 'all';

    public function handle(string $handle): self
    {
        $this->handle = $handle;
        return $this;
    }

    public function src(string $src): self
    {
        $this->src = $src;
        return $this;
    }

    public function deps(array $deps): self
    {
        $this->deps = $deps;
        return $this;
    }

    public function version(string|bool|null $version): self
    {
        $this->version = $version;
        return $this;
    }

    public function media(string $media): self
    {
        $this->media = $media;
        return $this;
    }

    /**
     * @return array{0: string, 1: string, 2: string[], 3: string|bool|null, 4: string}
     */
    public function rawArgs(): array
    {
        return [
            $this->handle,
            $this->src,
            array_map(static fn(IAssetDependency $dep) => $dep->getHandle(), $this->deps),
            $this->version,
            $this->media,
        ];
    }

    /**
     * @param array{0: string, 1: string, 2: string[], 3: string|bool|null, 4: string} $args
     */
    public function executeWithArgs(array $args): void
    {
        wp_enqueue_style(...$args);
    }
}
