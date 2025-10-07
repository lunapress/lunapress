<?php
declare(strict_types=1);

namespace LunaPress\Wp\Assets\WpRegisterScript;

use LunaPress\Wp\AssetsContracts\IAssetDependency;
use LunaPress\Wp\AssetsContracts\WpRegisterScript\IWpRegisterScriptArgs;
use LunaPress\Wp\AssetsContracts\WpRegisterScript\IWpRegisterScriptFunction;

defined('ABSPATH') || exit;

final class WpRegisterScript implements IWpRegisterScriptFunction
{
    private string $handle;
    private string|false $src = false;
    /** @var IAssetDependency[] */
    private array $deps                            = [];
    private string|bool|null $version              = false;
    private IWpRegisterScriptArgs|bool|array $args = [];

    public function handle(string $handle): self
    {
        $this->handle = $handle;
        return $this;
    }

    public function src(string|false $src): self
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

    public function args(IWpRegisterScriptArgs|bool $args): self
    {
        $this->args = $args;
        return $this;
    }

    /**
     * @return array{0: string, 1: string|false, 2: string[], 3: string|bool|null, 4: bool|IWpRegisterScriptArgs|array}
     */
    public function rawArgs(): array
    {
        return [
            $this->handle,
            $this->src,
            array_map(static fn($dep) => $dep->getHandle(), $this->deps),
            $this->version,
            $this->args,
        ];
    }

    /**
     * @param array{0: string, 1: string|false, 2: string[], 3: string|bool|null, 4: bool|array} $args
     */
    public function executeWithArgs(array $args): bool
    {
        return wp_register_script(...$args);
    }
}
