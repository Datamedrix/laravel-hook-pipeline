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
use DMX\Application\Pipeline\Contracts\HookHandlerInterface;

class HookHandlerMock implements HookHandlerInterface
{
    /**
     * @var bool
     */
    protected $stopPropagating = false;

    /**
     * @var bool
     */
    protected $dispatched = false;

    /**
     * @param bool|null $stop
     *
     * @return bool
     */
    public function stopPropagating(?bool $stop = null): bool
    {
        if ($stop !== null) {
            $this->stopPropagating = $stop;
        }

        return $this->stopPropagating;
    }

    /**
     * @param bool|null $dispatched
     *
     * @return bool
     */
    public function dispatched(?bool $dispatched = null): bool
    {
        if ($dispatched !== null) {
            $this->dispatched = $dispatched;
        }

        return $this->dispatched;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(HookInterface $event, ?string $context = null, ?array $additionalPayload = []): ?bool
    {
        $payload = $event->payload();
        if ($payload instanceof TestPayload) {
            ++$payload->touchCount;

            $event->setPayload($payload);
        }

        $this->dispatched(true);

        return !$this->stopPropagating();
    }
}
