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

if (function_exists('app')) {
    if (!function_exists('hook')) {
        /**
         * Dispatch an hook event and call the listeners.
         *
         * @param \DMX\Application\Pipeline\Contracts\HookInterface $event
         * @param string|null                                       $context
         * @param array|null                                        $additionalPayload
         *
         * @return mixed|null
         */
        function hook(\DMX\Application\Pipeline\Contracts\HookInterface $event, ?string $context = null, ?array $additionalPayload = [])
        {
            return app(\DMX\Application\Pipeline\HookDispatcher::class)->dispatch($event, $context, $additionalPayload);
        }
    }
}
