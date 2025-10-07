<?php
declare(strict_types=1);

namespace LunaPress\Core\Plugin;

use LunaPress\CoreContracts\Plugin\IContext;

defined('ABSPATH') || exit;

final readonly class PluginContext implements IContext
{
    public function __construct(
        private string $namespace,
        private string $prefix,
    ) {
    }

    public function getNamespace(): string {
        return $this->namespace;
    }
    public function getPrefix(): string {
        return $this->prefix;
    }
}
