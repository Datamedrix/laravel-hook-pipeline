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

use DMX\Application\Pipeline\Contracts\HookInterface;

class Hook implements HookInterface
{
    /**
     * @var string|null
     */
    protected $name = null;

    /**
     * @var mixed|null
     */
    protected $payload = null;

    /**
     * Hook constructor.
     *
     * @param mixed|null $payload
     */
    public function __construct($payload = null)
    {
        $this->payload = $payload;
    }

    /**
     * {@inheritdoc}
     */
    public function name(): string
    {
        return $this->name ?? static::class;
    }

    /**
     * {@inheritdoc}
     */
    public function payload()
    {
        return $this->payload;
    }

    /**
     * {@inheritdoc}
     *
     * @return Hook|HookInterface|self
     */
    public function setPayload($payload): self
    {
        $this->payload = $payload;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function toString(): string
    {
        return $this->name();
    }

    /**
     * Magic function.
     *
     * @see https://www.php.net/manual/en/language.oop5.magic.php#object.tostring
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->toString();
    }
}
