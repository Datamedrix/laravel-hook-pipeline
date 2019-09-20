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

namespace DMX\Application\Pipeline\Tests\Unit;

use PHPUnit\Framework\TestCase;
use DMX\Application\Pipeline\Hook;
use DMX\Application\Pipeline\Tests\Mocks\HookB;

class HookTest extends TestCase
{
    /**
     * Test.
     */
    public function testConstructorAndGetter()
    {
        $payload = [
            'foo' => rand(0, 9999),
            'BAR' => false,
        ];
        $hook = new Hook($payload);

        $this->assertEquals(Hook::class, $hook->name());
        $this->assertEquals($payload, $hook->payload());
    }

    /**
     * Test.
     */
    public function testSetContentMethod()
    {
        $payload = [
            'foo' => rand(0, 9999),
            'BAR' => false,
        ];
        $hook = new Hook();

        $this->assertEmpty($hook->payload());
        $this->assertNull($hook->payload());

        $this->assertEquals($hook, $hook->setPayload($payload));
        $this->assertEquals($payload, $hook->payload());

        $this->assertEquals($hook, $hook->setPayload(null));
        $this->assertNull($hook->payload());
    }

    /**
     * Test.
     */
    public function testToStringMethod()
    {
        $hook = new HookB();

        $this->assertEquals('hook_b', $hook->toString());
        $this->assertEquals($hook->name(), $hook->toString());

        // At least test the magic function too
        $this->assertEquals($hook->toString(), (string) $hook);
    }
}
