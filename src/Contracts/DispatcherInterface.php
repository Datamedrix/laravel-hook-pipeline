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

namespace DMX\Application\Pipeline\Contracts;

interface DispatcherInterface
{
    /**
     * Register an event listener with the dispatcher.
     *
     * @param string|array $events
     * @param mixed        $listener
     *
     * @return self|DispatcherInterface
     */
    public function listen($events, $listener);

    /**
     * Determine if a given event has listeners.
     *
     * @param string $eventName
     *
     * @return bool
     */
    public function hasListeners(string $eventName): bool;

    /**
     * Remove a set of listeners from the dispatcher.
     *
     * @param string $eventName
     *
     * @return self|DispatcherInterface
     */
    public function forget(string $eventName);

    /**
     * @param HookInterface $event
     *
     * @return mixed
     */
    public function dispatch(HookInterface $event);
}
