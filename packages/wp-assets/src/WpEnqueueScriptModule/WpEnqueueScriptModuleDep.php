<?php
declare(strict_types=1);

namespace LunaPress\Wp\Assets\WpEnqueueScriptModule;

use LunaPress\Wp\AssetsContracts\WpEnqueueScriptModule\Enum\WpEnqueueScriptModuleImport;
use LunaPress\Wp\AssetsContracts\WpEnqueueScriptModule\IWpEnqueueScriptModuleDep;

defined('ABSPATH') || exit;

final class WpEnqueueScriptModuleDep implements IWpEnqueueScriptModuleDep
{
    public function __construct(
        private string $id,
        private WpEnqueueScriptModuleImport $import = WpEnqueueScriptModuleImport::STATIC
    ) {
    }

    public static function of(string $id, WpEnqueueScriptModuleImport $import = WpEnqueueScriptModuleImport::STATIC): self
    {
        return new self($id, $import);
    }

    public static function dynamic(string $id): self
    {
        return new self($id, WpEnqueueScriptModuleImport::DYNAMIC);
    }

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
