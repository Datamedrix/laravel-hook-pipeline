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

interface HookInterface
{
    /**
     * @return string
     */
    public function name(): string;

    /**
     * @return mixed
     */
    public function payload();

    /**
     * @param $payload
     *
     * @return HookInterface|self
     */
    public function setPayload($payload);

    /**
     * @return string
     */
    public function toString(): string;
}
