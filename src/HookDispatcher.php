<?php
/**
 * ----------------------------------------------------------------------------
 * This code is part of an application or library developed by Datamedrix and
 * is subject to the provisions of your License Agreement with
 * Datamedrix GmbH.
 *
 * @copyright (c) 2019 Datamedrix GmbH
 * ----------------------------------------------------------------------------
 * @author Christian Graf <c.graf@datamedrix.com>
 */

declare(strict_types=1);

namespace DMX\Application\Pipeline;

use Illuminate\Contracts\Foundation\Application;
use DMX\Application\Pipeline\Contracts\HookInterface;
use DMX\Application\Pipeline\Contracts\DispatcherInterface;
use DMX\Application\Pipeline\Contracts\HookHandlerInterface;
use DMX\Application\Pipeline\Exceptions\InvalidHookHandlerException;

class HookDispatcher implements DispatcherInterface
{
    /**
     * @var Application
     */
    protected $container;

    /**
     * The registered hook listeners.
     *
     * @var array
     */
    protected $listeners = [];

    /**
     * HookDispatcher constructor.
     *
     * @param Application $applicationContainer
     */
    public function __construct(Application $applicationContainer)
    {
        $this->container = $applicationContainer;
    }

    /**
     * Create a class based listener using the IoC container.
     *
     * @param string $listener
     *
     * @return \Closure
     */
    protected function createClassListener(string $listener): \Closure
    {
        return function (HookInterface $event, ?string $context, ?array $additionalPayload = []) use ($listener) {
            return call_user_func_array(
                [$this->container->make($listener), 'handle'],
                [
                    $event,
                    $context,
                    $additionalPayload,
                ]
            );
        };
    }

    /**
     * Register an event listener with the dispatcher.
     *
     * @param \Closure|string $listener
     *
     * @return \Closure
     *
     * @throws InvalidHookHandlerException if the given listener is not valid
     */
    protected function makeListener($listener): \Closure
    {
        if (!($listener instanceof HookHandlerInterface) && !($listener instanceof \Closure) && !is_string($listener)) {
            throw new InvalidHookHandlerException('The given listener is not valid!');
        }

        if (is_string($listener)) {
            return $this->createClassListener($listener);
        }

        if ($listener instanceof HookHandlerInterface) {
            return function (HookInterface $event, ?string $context, ?array $additionalPayload = []) use ($listener) {
                return $listener->handle($event, $context, $additionalPayload);
            };
        }

        return $listener;
    }

    /**
     * {@inheritdoc}
     *
     * @return HookDispatcher
     *
     * @throws InvalidHookHandlerException if the given listener is not valid
     */
    public function listen($events, $listener): self
    {
        foreach ((array) $events as $event) {
            $this->listeners[(string) $event][] = $this->makeListener($listener);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasListeners(string $eventName): bool
    {
        return !empty($this->listeners[$eventName]);
    }

    /**
     * {@inheritdoc}
     *
     * @return HookDispatcher
     */
    public function forget(string $eventName): self
    {
        if (isset($this->listeners[$eventName])) {
            unset($this->listeners[$eventName]);
        }

        return $this;
    }

    /**
     * @param string $eventName
     *
     * @return int
     */
    public function getListenerCount(string $eventName): int
    {
        if ($this->hasListeners($eventName)) {
            return count($this->listeners[$eventName]);
        }

        return 0;
    }

    /**
     * @return int
     */
    public function getRegisteredHookCount(): int
    {
        return count($this->listeners);
    }

    /**
     * @param HookInterface $event
     *
     * @return array
     */
    public function getListeners(HookInterface $event): array
    {
        if ($event->name() !== get_class($event)) {
            return array_merge(
                $this->listeners[get_class($event)] ?? [],
                $this->listeners[$event->name()] ?? []
            );
        }

        return $this->listeners[$event->name()] ?? [];
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(HookInterface $event, ?string $context = null, ?array $additionalPayload = [])
    {
        $payload = $event->payload();
        foreach ($this->getListeners($event) as $listener) {
            $propagating = $listener($event, $context, $additionalPayload);
            $payload = $event->payload();

            if ($propagating === false) {
                break;
            }
        }

        return $payload;
    }
}
