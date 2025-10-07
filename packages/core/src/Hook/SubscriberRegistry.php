<?php
declare(strict_types=1);

namespace LunaPress\Core\Hook;

use LunaPress\CoreContracts\Hook\ActionSubscriber;
use LunaPress\CoreContracts\Hook\DelayedSubscriber;
use LunaPress\CoreContracts\Hook\FilterSubscriber;
use LunaPress\CoreContracts\Hook\Subscriber;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionClass;
use LunaPress\CoreContracts\Hook\ISubscriberRegistry;

defined('ABSPATH') || exit;

final readonly class SubscriberRegistry implements ISubscriberRegistry
{
    public function __construct(
        private ContainerInterface     $container,
        private ActionManager $actions,
        private FilterManager $filters,
    ) {
    }

    /**
     * @param array<class-string<Subscriber>|Subscriber> $subscribers
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

    public function register(Subscriber $subscriber): void
    {
        $ref = new ReflectionClass($subscriber);

        foreach ($ref->getAttributes(Hook::class) as $attr) {
            /** @var Hook $hook */
            $hook = $attr->newInstance();

            if ($subscriber instanceof ActionSubscriber) {
                $this->registerAction($subscriber, $hook);
            } elseif ($subscriber instanceof FilterSubscriber) {
                $this->registerFilter($subscriber, $hook);
            }
        }
    }

    private function registerAction(Subscriber $subscriber, Hook $hook): void
    {
        $callback = $subscriber->callback();

        if ($subscriber instanceof DelayedSubscriber) {
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

    private function registerFilter(Subscriber $subscriber, Hook $hook): void
    {
        $callback = $subscriber->callback();

        if ($subscriber instanceof DelayedSubscriber) {
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
