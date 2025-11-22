<?php
declare(strict_types=1);

namespace LunaPress\Wp\Assets\Function\WpEnqueueScript;

use LunaPress\FoundationContracts\Support\WpFunction\WpArray;
use LunaPress\Wp\AssetsContracts\Entity\AssetDependency\IAssetDependency;
use LunaPress\Wp\AssetsContracts\Function\WpEnqueueScript\IWpEnqueueScriptArgs;
use LunaPress\Wp\AssetsContracts\Function\WpEnqueueScript\IWpEnqueueScriptFunction;

defined('ABSPATH') || exit;

final class WpEnqueueScript implements IWpEnqueueScriptFunction
{
    private string $handle;
    private string $src = '';

    /**
     * @var IAssetDependency[]
     */
    private array $deps                             = [];
    private string|bool $version                    = false;
    private IWpEnqueueScriptArgs|WpArray|bool $args = WpArray::Empty;

    public function rawArgs(): array
    {
        return [
            $this->getHandle(),
            $this->getSrc(),
            $this->getDeps(),
            $this->getVersion(),
            $this->getArgs(),
        ];
    }

    public function executeWithArgs(array $args): void
    {
        wp_enqueue_script(...$args);
    }

    public function getHandle(): string
    {
        return $this->handle;
    }

    public function getSrc(): string
    {
        return $this->src;
    }

    /**
     * @inheritDoc
     */
    public function getDeps(): array
    {
        return $this->deps;
    }

    public function getVersion(): string|bool
    {
        return $this->version;
    }

    public function getArgs(): IWpEnqueueScriptArgs|WpArray|bool
    {
        return $this->args;
    }

    public function handle(string $handle): IWpEnqueueScriptFunction
    {
        $this->handle = $handle;
        return $this;
    }

    public function src(string $src): IWpEnqueueScriptFunction
    {
        $this->src = $src;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function deps(array $deps): IWpEnqueueScriptFunction
    {
        $this->deps = $deps;
        return $this;
    }

    public function version(bool|string $version): IWpEnqueueScriptFunction
    {
        $this->version = $version;
        return $this;
    }

    public function args(bool|IWpEnqueueScriptArgs|WpArray $args): IWpEnqueueScriptFunction
    {
        $this->args = $args;
        return $this;
    }
}
