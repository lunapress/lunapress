<?php
declare(strict_types=1);

namespace LunaPress\Core\Loader;

use LunaPress\CoreContracts\Subscriber\ISubscriberRegistry;
use LunaPress\CoreContracts\Support\ILoader;
use LunaPress\FoundationContracts\Module\IHasModules;
use LunaPress\FoundationContracts\Module\IModule;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

defined('ABSPATH') || exit;

final readonly class ModuleLoader implements ILoader
{
    public function __construct(
        private IHasModules $hasModules,
        private ContainerInterface $container,
    ) {
    }

    /**
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function load(): void
    {
        $registry = $this->container->get(ISubscriberRegistry::class);

        foreach ($this->hasModules->getModules() as $moduleClass) {
            /** @var IModule $module */
            $module = is_string($moduleClass)
                ? $this->container->get($moduleClass)
                : $moduleClass;

            if (!$module instanceof IModule) {
                continue;
            }

            $registry->registerMany($module->subscribers());
        }
    }
}
