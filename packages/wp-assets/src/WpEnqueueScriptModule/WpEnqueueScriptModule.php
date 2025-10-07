<?php
declare(strict_types=1);

namespace LunaPress\Wp\Assets\WpEnqueueScriptModule;

use LunaPress\Wp\AssetsContracts\WpEnqueueScriptModule\IWpEnqueueScriptModuleFunction;

defined('ABSPATH') || exit;

final class WpEnqueueScriptModule implements IWpEnqueueScriptModuleFunction
{
    private string $id;
    private string $src = '';

    /**
     * @var WpEnqueueScriptModuleDeps[] $deps
     */
    private array $deps                = [];
    private false|string|null $version = false;

    public function rawArgs(): array
    {
        return [$this->id, $this->src, $this->deps, $this->version];
    }

    public function id(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function src(string $src): self
    {
        $this->src = $src;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function deps(array $deps): self
    {
        $this->deps = $deps;

        return $this;
    }

    public function version(false|string|null $version): self
    {
        $this->version = $version;

        return $this;
    }

    public function executeWithArgs(array $args): void
    {
        wp_enqueue_script_module(...$args);
    }
}
