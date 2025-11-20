<?php
declare(strict_types=1);

namespace LunaPress\Core\Loader;

use LunaPress\CoreContracts\Plugin\IPlugin;
use LunaPress\FoundationContracts\Support\ILoader;
use ReflectionClass;

defined('ABSPATH') || exit;

final readonly class LifecycleLoader implements ILoader
{
    public function __construct(private IPlugin $plugin) {
    }

    public function load(): void
    {
        $ref  = new ReflectionClass($this->plugin);
        $file = $ref->getFileName();

        register_activation_hook($file, [$this->plugin, 'activate']);
        register_deactivation_hook($file, [$this->plugin, 'deactivate']);
    }
}
