<?php
declare(strict_types=1);

namespace LunaPress\Wp\Assets\WpEnqueueScriptModule;

use LunaPress\Wp\AssetsContracts\WpEnqueueScriptModule\Enum\WpEnqueueScriptModuleImport;
use LunaPress\Wp\AssetsContracts\WpEnqueueScriptModule\IWpEnqueueScriptModuleDeps;

defined('ABSPATH') || exit;

final readonly class WpEnqueueScriptModuleDeps implements IWpEnqueueScriptModuleDeps
{
    private string $id;
    private WpEnqueueScriptModuleImport $import;

    public function toArray(): array
    {

        return [
            'id' => $this->id,
            'import' => $this->import->value,
        ];
    }

    public function id(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function import(WpEnqueueScriptModuleImport $import): self
    {
        $this->import = $import;

        return $this;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getImport(): WpEnqueueScriptModuleImport
    {
        return $this->import;
    }
}
