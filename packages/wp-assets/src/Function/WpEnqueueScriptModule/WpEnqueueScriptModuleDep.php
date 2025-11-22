<?php
declare(strict_types=1);

namespace LunaPress\Wp\Assets\Function\WpEnqueueScriptModule;

use LunaPress\FoundationContracts\Support\WpFunction\WpUnset;
use LunaPress\Wp\AssetsContracts\Enum\ScriptModuleImport;
use LunaPress\Wp\AssetsContracts\Function\WpEnqueueScriptModule\IWpEnqueueScriptModuleDep;

defined('ABSPATH') || exit;

final class WpEnqueueScriptModuleDep implements IWpEnqueueScriptModuleDep
{
    public function __construct(
        private string $id,
        private ScriptModuleImport|WpUnset $import = WpUnset::Value
    ) {
    }

    public static function of(string $id, ScriptModuleImport|WpUnset $import = ScriptModuleImport::STATIC): self
    {
        return new self($id, $import);
    }

    public static function dynamic(string $id): self
    {
        return new self($id, ScriptModuleImport::DYNAMIC);
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

    public function import(ScriptModuleImport|WpUnset $import): self
    {
        $this->import = $import;

        return $this;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getImport(): ScriptModuleImport|WpUnset
    {
        return $this->import;
    }
}
