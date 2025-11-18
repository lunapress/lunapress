<?php
declare(strict_types=1);

namespace LunaPress\Wp\Assets\WpEnqueueScript;

use LunaPress\Wp\AssetsContracts\IAssetDependency;
use LunaPress\Wp\AssetsContracts\WpEnqueueScript\IWpEnqueueScriptArgs;
use LunaPress\Wp\AssetsContracts\WpEnqueueScript\IWpEnqueueScriptFunction;

defined('ABSPATH') || exit;

final class WpEnqueueScript implements IWpEnqueueScriptFunction
{
    private string $handle;
    private ?string $src = null;

    /**
     * @var IAssetDependency[]|null
     */
    private ?array $deps                         = null;
    private string|bool|null $version            = null;
    private IWpEnqueueScriptArgs|bool|null $args = null;

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

    public function getSrc(): ?string
    {
        return $this->src;
    }

    /**
     * @inheritDoc
     */
    public function getDeps(): ?array
    {
        return $this->deps;
    }

    public function getVersion(): string|bool|null
    {
        return $this->version;
    }

    public function getArgs(): IWpEnqueueScriptArgs|bool|null
    {
        return $this->args;
    }

    public function handle(string $handle): IWpEnqueueScriptFunction
    {
        $this->handle = $handle;
        return $this;
    }

    public function src(?string $src): IWpEnqueueScriptFunction
    {
        $this->src = $src;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function deps(?array $deps): IWpEnqueueScriptFunction
    {
        $this->deps = $deps;
        return $this;
    }

    public function version(bool|string|null $version): IWpEnqueueScriptFunction
    {
        $this->version = $version;
        return $this;
    }

    public function args(bool|IWpEnqueueScriptArgs|null $args): IWpEnqueueScriptFunction
    {
        $this->args = $args;
        return $this;
    }
}
