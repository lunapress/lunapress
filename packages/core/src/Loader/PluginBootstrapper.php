<?php

declare(strict_types=1);

namespace LunaPress\Core\Loader;

use LunaPress\CoreContracts\Plugin\Plugin;
use LunaPress\CoreContracts\Subscriber\SubscriberRegistry;
use LunaPress\FoundationContracts\Module\HasModules;
use LunaPress\FoundationContracts\Module\Module;
use LunaPress\FoundationContracts\Package\Package;
use Psr\Container\ContainerInterface;
use function is_string;

final readonly class PluginBootstrapper
{
    public function __construct(
        private ContainerInterface $container,
    ) {
    }

    public function boot(Plugin $plugin): void
    {
        $this->bootPackages($plugin);
        $this->bootModules($plugin);
        $this->registerLifecycleHooks($plugin);
    }

    private function registerLifecycleHooks(Plugin $plugin): void
    {
        $file = $plugin->getCallerFile();

        register_activation_hook($file, [$plugin, 'activate']);
        register_deactivation_hook($file, [$plugin, 'deactivate']);
    }

    private function bootPackages(Plugin $plugin): void
    {
        foreach ($plugin->getPackages() as $packageClass) {
            $package = is_string($packageClass) ? $this->container->get($packageClass) : $packageClass;

            if (!($package instanceof Package)) {
				continue;
			}

			$this->loadModules($package);
        }
    }

    private function bootModules(HasModules $target): void
    {
        $this->loadModules($target);
    }

    private function loadModules(HasModules $target): void
    {
        /**
         * @var SubscriberRegistry $registry
         */
        $registry = $this->container->get(SubscriberRegistry::class);

        foreach ($target->getModules() as $moduleClass) {
            $module = is_string($moduleClass) ? $this->container->get($moduleClass) : $moduleClass;

            if (!($module instanceof Module)) {
				continue;
			}

			$registry->registerMany($module->subscribers());
        }
    }
}
