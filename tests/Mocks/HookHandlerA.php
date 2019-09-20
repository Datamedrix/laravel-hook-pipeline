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

namespace DMX\Application\Pipeline\Tests\Mocks;

use DMX\Application\Pipeline\Contracts\HookInterface;

class HookHandlerA extends HookHandlerMock
{
    /**
     * {@inheritdoc}
     */
    public function handle(HookInterface $event, ?string $context = null, ?array $additionalPayload = []): ?bool
    {
        $event->payload()->data['A'] = true;

        return parent::handle($event, $context, $additionalPayload);
    }
}
