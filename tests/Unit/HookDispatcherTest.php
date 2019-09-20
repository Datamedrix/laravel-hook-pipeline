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
use DMX\Application\Pipeline\HookDispatcher;
use DMX\Application\Pipeline\Tests\Mocks\HookA;
use DMX\Application\Pipeline\Tests\Mocks\HookB;
use DMX\Application\Pipeline\Tests\Mocks\HookC;
use DMX\Application\Pipeline\Tests\Mocks\HookD;
use Illuminate\Contracts\Foundation\Application;
use DMX\Application\Pipeline\Tests\Mocks\TestPayload;
use DMX\Application\Pipeline\Tests\Mocks\HookHandlerA;
use DMX\Application\Pipeline\Tests\Mocks\HookHandlerB;
use DMX\Application\Pipeline\Tests\Mocks\HookHandlerC;
use DMX\Application\Pipeline\Exceptions\InvalidHookHandlerException;

class HookDispatcherTest extends TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|Application
     */
    private $appMock;

    /**
     * @var HookDispatcher
     */
    private $dispatcher;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->appMock = $this->getMockBuilder(Application::class)->getMock();
        $this->appMock
            ->expects($this->any())
            ->method('make')
            ->willReturn(new HookHandlerA())
        ;
        $this->dispatcher = new HookDispatcher($this->appMock);
    }

    /**
     * Test.
     */
    public function testRegisterWorkflow()
    {
        // 1. register some listeners
        $this->dispatcher->listen('fooBar', 'bar');
        $this->dispatcher->listen(HookA::class, 'foo');

        // 2. check the designated listeners are registered
        $this->assertEquals(2, $this->dispatcher->getRegisteredHookCount());
        $this->assertTrue($this->dispatcher->hasListeners('fooBar'));
        $this->assertTrue($this->dispatcher->hasListeners(HookA::class));
        $this->assertFalse($this->dispatcher->hasListeners(HookB::class));

        // 3. remove some listeners and check it
        $this->dispatcher->forget('fooBar');
        $this->assertEquals(1, $this->dispatcher->getRegisteredHookCount());
        $this->assertFalse($this->dispatcher->hasListeners('fooBar'));
    }

    /**
     * Test.
     */
    public function testCountingMethods()
    {
        $this->dispatcher->listen('fooBar', 'bar');
        $this->dispatcher->listen(HookA::class, 'foo');
        $this->dispatcher->listen(HookA::class, 'bar');
        $this->dispatcher->listen(HookA::class, 'fooBar');

        $this->assertEquals(2, $this->dispatcher->getRegisteredHookCount());
        $this->assertEquals(1, $this->dispatcher->getListenerCount('fooBar'));
        $this->assertEquals(3, $this->dispatcher->getListenerCount(HookA::class));
        $this->assertEquals(0, $this->dispatcher->getListenerCount('not-registered'));

        $this->dispatcher->listen(HookA::class, '1234');
        $this->assertEquals(4, $this->dispatcher->getListenerCount(HookA::class));
    }

    /**
     * Test.
     */
    public function testDispatching()
    {
        // 1. dispatch a hook event without listeners
        $payload = new TestPayload();
        $payload->comments = 'default c0mm3NT';
        $payload->data = ['foo' => 'BAR'];
        $payload->touchCount = rand(-1, -999);
        $hook = new HookD($payload);
        $this->assertEquals($payload, $this->dispatcher->dispatch($hook, null, null));

        $hook = new HookD('My T3xt');
        $this->assertEquals('My T3xt', $this->dispatcher->dispatch($hook, null, null));

        // 2. dispatch with some listeners
        $this->dispatcher->listen(HookA::class, new HookHandlerA());
        $this->dispatcher->listen(HookA::class, new HookHandlerB());
        $this->dispatcher->listen(HookA::class, new HookHandlerC());
        $this->dispatcher->listen(HookA::class, function (HookA $event, ?string $context = null, ?array $more = []) {
            $event->payload()->comments = 'CLOSURE WAS HERE';
        });
        $hook = new HookA(new TestPayload());
        $payload = $this->dispatcher->dispatch($hook, null, null);
        $this->assertNotEmpty($payload);
        $this->assertInstanceOf(TestPayload::class, $payload);
        $this->assertEquals(3, $payload->touchCount);
        $this->assertEquals([
                'A' => true,
                'B' => true,
                'C' => true,
            ],
            $payload->data
        );
        $this->assertEquals('CLOSURE WAS HERE', $payload->comments);

        // at least check the error handling
        $this->expectException(InvalidHookHandlerException::class);
        $this->dispatcher->listen(HookC::class, 45);
    }
}
