<?php
declare(strict_types=1);

namespace LunaPress\Wp\Assets\WpEnqueueScriptModule;

use LunaPress\FoundationContracts\Support\WpFunction\WpUnset;
use LunaPress\Wp\AssetsContracts\WpEnqueueScriptModule\Enum\WpEnqueueScriptModuleImport;
use LunaPress\Wp\AssetsContracts\WpEnqueueScriptModule\IWpEnqueueScriptModuleDep;

defined('ABSPATH') || exit;

final class WpEnqueueScriptModuleDep implements IWpEnqueueScriptModuleDep
{
    public function __construct(
        private string $id,
        private WpEnqueueScriptModuleImport|WpUnset $import = WpUnset::Value
    ) {
    }

    public static function of(string $id, WpEnqueueScriptModuleImport|WpUnset $import = WpEnqueueScriptModuleImport::STATIC): self
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

    public function import(WpEnqueueScriptModuleImport|WpUnset $import): self
    {
        $this->import = $import;

        return $this;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getImport(): WpEnqueueScriptModuleImport|WpUnset
    {
        return $this->import;
    }
}
