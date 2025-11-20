<?php
declare(strict_types=1);

namespace LunaPress\Core\Subscriber;

use LunaPress\Core\Hook\ActionManager;
use LunaPress\Core\Hook\FilterManager;
use LunaPress\Core\Hook\Hook;
use LunaPress\FoundationContracts\Subscriber\IActionSubscriber;
use LunaPress\FoundationContracts\Subscriber\IDelayedSubscriber;
use LunaPress\FoundationContracts\Subscriber\IFilterSubscriber;
use LunaPress\FoundationContracts\Subscriber\ISubscriber;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionClass;
use LunaPress\CoreContracts\Subscriber\ISubscriberRegistry;

defined('ABSPATH') || exit;

final readonly class SubscriberRegistry implements ISubscriberRegistry
{
    public function __construct(
        private ContainerInterface $container,
        private ActionManager $actions,
        private FilterManager $filters,
    ) {
    }

    /**
     * @param array<class-string<ISubscriber>|ISubscriber> $subscribers
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function registerMany(array $subscribers): void
    {
        foreach ($subscribers as $subscriber) {
            $instance = is_string($subscriber)
                ? $this->container->get($subscriber)
                : $subscriber;

            $this->register($instance);
        }
    }

    public function register(ISubscriber $subscriber): void
    {
        $ref = new ReflectionClass($subscriber);

        foreach ($ref->getAttributes(Hook::class) as $attr) {
            /** @var Hook $hook */
            $hook = $attr->newInstance();

            if ($subscriber instanceof IActionSubscriber) {
                $this->registerAction($subscriber, $hook);
            } elseif ($subscriber instanceof IFilterSubscriber) {
                $this->registerFilter($subscriber, $hook);
            }
        }
    }

    private function registerAction(ISubscriber $subscriber, Hook $hook): void
    {
        $callback = $subscriber->callback();

        if ($subscriber instanceof IDelayedSubscriber) {
            $this->actions->add(
                $subscriber::afterHook(),
                fn() => $this->actions->add($hook->getName(), $callback, $hook->getPriority(), $hook->getAcceptedArgs()),
                $subscriber::afterPriority(),
                $subscriber::afterArgs(),
            );
        } else {
            $this->actions->add($hook->getName(), $callback, $hook->getPriority(), $hook->getAcceptedArgs());
        }
    }

    private function registerFilter(ISubscriber $subscriber, Hook $hook): void
    {
        $callback = $subscriber->callback();

        if ($subscriber instanceof IDelayedSubscriber) {
            $this->filters->add(
                $subscriber::afterHook(),
                fn() => $this->filters->add($hook->getName(), $callback, $hook->getPriority(), $hook->getAcceptedArgs()),
                $subscriber::afterPriority(),
                $subscriber::afterArgs(),
            );
        } else {
            $this->filters->add($hook->getName(), $callback, $hook->getPriority(), $hook->getAcceptedArgs());
        }
    }
}
