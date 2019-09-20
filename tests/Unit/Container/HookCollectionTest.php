<?php
/**
 * ----------------------------------------------------------------------------
 * This code is part of an application or library developed by Datamedrix and
 * is subject to the provisions of your License Agreement with
 * Datamedrix GmbH.
 *
 * @copyright (c) 2018 Datamedrix GmbH
 * ----------------------------------------------------------------------------
 * @author Christian Graf <c.graf@datamedrix.com>
 */

declare(strict_types=1);

namespace DMX\Application\Pipeline\Tests\Unit\Container;

use PHPUnit\Framework\TestCase;
use DMX\Application\Pipeline\Tests\Mocks\HookA;
use DMX\Application\Pipeline\Tests\Mocks\HookB;
use DMX\Application\Pipeline\Tests\Mocks\HookC;
use DMX\Application\Pipeline\Tests\Mocks\HookD;
use DMX\Application\Pipeline\Contracts\HookInterface;
use DMX\Application\Pipeline\Container\HookCollection;
use DMX\Application\Pipeline\Exceptions\HookNotFoundException;

class HookCollectionTest extends TestCase
{
    /**
     * Get a list of invalid items.
     *
     * @return array
     */
    public function getInvalidItems(): array
    {
        return [
            [rand(0, 9999)],
            ['i am a String'],
            [['foo' => 'Bar']],
            [(object) ['foo' => 'Bar']],
        ];
    }

    /**
     * Test.
     */
    public function testConstructor()
    {
        // 1. set only required parameters / using default values
        $collection = new HookCollection();
        $this->assertEmpty($collection);
        $this->assertEquals(0, $collection->count());

        // 2. with predefined list
        $items = [
            new HookA(),
            new HookB(),
            new HookC(),
            new HookD(),
        ];

        $collection = new HookCollection($items);

        $this->assertNotEmpty($collection);
        $this->assertEquals(4, $collection->count());
        $this->assertEquals($items, $collection->all());
    }

    /**
     * Test.
     *
     * @param mixed $invalidItem
     * @dataProvider getInvalidItems
     */
    public function testMethodPushThrowsAnInvalidArgumentException($invalidItem)
    {
        $collection = new HookCollection();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The given item is not a valid "' . HookInterface::class . '" instance.');

        $collection->push($invalidItem);
    }

    /**
     * Test.
     */
    public function testMethodPush()
    {
        $collection = new HookCollection();
        $this->assertEquals(0, $collection->count());

        $collection->push(new HookA());
        $this->assertEquals(1, $collection->count());

        $collection->push(new HookB());
        $this->assertEquals(2, $collection->count());
    }

    /**
     * Test.
     */
    public function testMethodTimesThrowsAnBadMethodCallException()
    {
        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('This method is not realizable for this collection!');

        HookCollection::times(rand(1, 9999));
    }

    /**
     * Test.
     */
    public function testMethodAvgThrowsAnBadMethodCallException()
    {
        $collection = new HookCollection();

        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('This method is not realizable for this collection!');

        $collection->avg();
    }

    /**
     * Test.
     */
    public function testMethodAverageThrowsAnBadMethodCallException()
    {
        $collection = new HookCollection();

        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('This method is not realizable for this collection!');

        $collection->average();
    }

    /**
     * Test.
     */
    public function testMethodMedianThrowsAnBadMethodCallException()
    {
        $collection = new HookCollection();

        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('This method is not realizable for this collection!');

        $collection->median();
    }

    /**
     * Test.
     */
    public function testMethodModeThrowsAnBadMethodCallException()
    {
        $collection = new HookCollection();

        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('This method is not realizable for this collection!');

        $collection->mode();
    }

    /**
     * Test.
     */
    public function testMethodFlipThrowsAnBadMethodCallException()
    {
        $collection = new HookCollection();

        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('This method is not realizable for this collection!');

        $collection->flip();
    }

    /**
     * Test.
     */
    public function testMethodCollapseThrowsAnBadMethodCallException()
    {
        $collection = new HookCollection();

        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('This method is not realizable for this collection!');

        $collection->collapse();
    }

    /**
     * Test.
     */
    public function testMethodGroupByThrowsAnBadMethodCallException()
    {
        $collection = new HookCollection();

        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('This method is not realizable for this collection!');

        $collection->groupBy([]);
    }

    /**
     * Test.
     */
    public function testMethodExceptThrowsAnBadMethodCallException()
    {
        $collection = new HookCollection();

        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('This method is not realizable for this collection!');

        $collection->except([]);
    }

    /**
     * Test.
     */
    public function testMethodFlattenThrowsAnBadMethodCallException()
    {
        $collection = new HookCollection();

        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('This method is not realizable for this collection!');

        $collection->flatten();
    }

    /**
     * Test.
     */
    public function testMethodKeyByThrowsAnBadMethodCallException()
    {
        $collection = new HookCollection();

        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('This method is not realizable for this collection!');

        $collection->keyBy([]);
    }

    /**
     * Test.
     */
    public function testMethodJoinThrowsAnBadMethodCallException()
    {
        $collection = new HookCollection();

        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('This method is not realizable for this collection!');

        $collection->join('');
    }

    /**
     * Test.
     */
    public function testMethodMapToDictionaryThrowsAnBadMethodCallException()
    {
        $collection = new HookCollection();

        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('This method is not realizable for this collection!');

        $collection->mapToDictionary(function () {
            return null;
        });
    }

    /**
     * Test.
     */
    public function testMethodMapWithKeysThrowsAnBadMethodCallException()
    {
        $collection = new HookCollection();

        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('This method is not realizable for this collection!');

        $collection->mapWithKeys(function () {
            return null;
        });
    }

    /**
     * Test.
     */
    public function testMethodMergeRecursiveThrowsAnBadMethodCallException()
    {
        $collection = new HookCollection();

        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('This method is not realizable for this collection!');

        $collection->mergeRecursive('');
    }

    /**
     * Test.
     */
    public function testMethodNthThrowsAnBadMethodCallException()
    {
        $collection = new HookCollection();

        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('This method is not realizable for this collection!');

        $collection->nth(100);
    }

    /**
     * Test.
     */
    public function testMethodSpliceThrowsAnBadMethodCallException()
    {
        $collection = new HookCollection();

        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('This method is not realizable for this collection!');

        $collection->splice(4);
    }

    /**
     * Test.
     */
    public function testMethodSplitThrowsAnBadMethodCallException()
    {
        $collection = new HookCollection();

        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('This method is not realizable for this collection!');

        $collection->split(2);
    }

    /**
     * Test.
     */
    public function testMethodReplaceRecursiveThrowsAnBadMethodCallException()
    {
        $collection = new HookCollection();

        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('This method is not realizable for this collection!');

        $collection->replaceRecursive([]);
    }

    /**
     * Test.
     */
    public function testMethodChunkThrowsAnBadMethodCallException()
    {
        $collection = new HookCollection();

        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('This method is not realizable for this collection!');

        $collection->chunk(5);
    }

    /**
     * Test.
     */
    public function testMethodTransformThrowsAnBadMethodCallException()
    {
        $collection = new HookCollection();

        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('This method is not realizable for this collection!');

        $collection->transform(function () {
            return null;
        });
    }

    /**
     * Test.
     */
    public function testMethodZipThrowsAnBadMethodCallException()
    {
        $collection = new HookCollection();

        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('This method is not realizable for this collection!');

        $collection->zip([]);
    }

    /**
     * Test.
     */
    public function testMethodPadThrowsAnBadMethodCallException()
    {
        $collection = new HookCollection();

        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('This method is not realizable for this collection!');

        $collection->pad(3, []);
    }

    /**
     * Test.
     */
    public function testMethodTakeThrowsAnBadMethodCallException()
    {
        $collection = new HookCollection();

        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('This method is not realizable for this collection!');

        $collection->take(33);
    }

    /**
     * Test.
     */
    public function testMethodContains()
    {
        $items = [
            new HookA(),
            new HookB(),
            new HookC(),
        ];
        $collection = new HookCollection($items);

        $this->assertTrue($collection->contains(HookA::class));
        $this->assertTrue($collection->contains('hook_b'));
        $this->assertTrue($collection->contains(HookB::class));
        $this->assertTrue($collection->contains('hook_c'));
        $this->assertTrue($collection->contains('hook_c', '>', 100));
        $this->assertFalse($collection->contains('foo', '>', 100));

        $this->assertTrue($collection->contains(new HookC()));
        $this->assertFalse($collection->contains(new HookD()));
    }

    /**
     * Test.
     */
    public function testMethodFind()
    {
        $items = [
            new HookA(),
            new HookB(),
            new HookC(),
        ];
        $collection = new HookCollection($items);

        // 1. try to find items
        $item = $collection->find(HookA::class);
        $this->assertNotEmpty($item);
        $this->assertEquals(HookA::class, $item->name());

        $item = $collection->find(HookB::class);
        $this->assertNotEmpty($item);
        $this->assertEquals('hook_b', $item->name());

        $item = $collection->find('hook_c');
        $this->assertNotEmpty($item);
        $this->assertEquals('hook_c', $item->name());

        // 2. looking for an unknown item
        $item = $collection->find('unknown-' . rand(10, 99));
        $this->assertNull($item);

        // 3. looking for an object on an empty list
        $collection = new HookCollection();
        $item = $collection->find('Hook 1');
        $this->assertNull($item);
    }

    /**
     * Test.
     */
    public function testMethodFindOrFail()
    {
        $items = [
            new HookA(),
            new HookB(),
            new HookC(),
        ];
        $collection = new HookCollection($items);

        // 1. try to find items
        $item = $collection->findOrFail('hook_c');
        $this->assertNotEmpty($item);
        $this->assertEquals('hook_c', $item->name());

        // 2. looking for an unknown item
        $key = 'unknown-' . rand(10, 99);
        $this->expectException(HookNotFoundException::class);
        $this->expectExceptionMessage('No hook found matched with name "' . $key . '".');
        $collection->findOrFail($key);
    }

    /**
     * Test.
     */
    public function testMethodOffsetExists()
    {
        $items = [
            new HookA(),
            new HookB(),
            new HookC(),
        ];
        $collection = new HookCollection($items);

        $this->assertTrue($collection->offsetExists('hook_b'));
        $this->assertTrue($collection->offsetExists(2));
        $this->assertTrue(isset($collection['hook_b']));
        $this->assertTrue(isset($collection[1]));

        $this->assertFalse($collection->offsetExists('foo-bar'));
        $this->assertFalse($collection->offsetExists(10));
        $this->assertFalse(isset($collection['foo-bar']));
        $this->assertFalse(isset($collection[10]));
    }

    /**
     * Test.
     */
    public function testMethodOffsetGet()
    {
        $items = [
            new HookA(),
            new HookB(),
            new HookC(),
        ];
        $collection = new HookCollection($items);

        $item = $collection->offsetGet('hook_b');
        $this->assertInstanceOf(HookB::class, $item);
        $this->assertEquals('hook_b', $item->name());

        $item = $collection['hook_c'];
        $this->assertInstanceOf(HookC::class, $item);
        $this->assertEquals('hook_c', $item->name());

        $item = $collection[2];
        $this->assertInstanceOf(HookC::class, $item);
        $this->assertEquals('hook_c', $item->name());

        $item = $collection[2.5];
        $this->assertEmpty($item);
    }

    /**
     * Test.
     */
    public function testMethodOffsetUnset()
    {
        $items = [
            new HookA(),
            new HookB(),
            new HookC(),
        ];
        $collection = new HookCollection($items);

        $this->assertEquals(3, $collection->count());

        $collection->offsetUnset('hook_b');
        $this->assertEquals(2, $collection->count());
        $this->assertFalse($collection->offsetExists('hook_b'));

        unset($collection[0]);
        $this->assertEquals(1, $collection->count());
        $this->assertFalse($collection->offsetExists(HookA::class));
    }

    /**
     * Test.
     */
    public function testMethodSearch()
    {
        $items = [
            new HookA(),
            new HookB(),
            new HookC(),
        ];
        $collection = new HookCollection($items);

        // 1. try to search for items
        $key = $collection->search(HookA::class);
        $this->assertEquals(0, $key);

        $key = $collection->search('hook_c');
        $this->assertEquals(2, $key);

        // 2. looking for an unknown item
        $key = $collection->search('unknown-' . rand(10, 99));
        $this->assertFalse($key);

        // 3. looking for an object on an empty list
        $collection = new HookCollection();
        $key = $collection->search('Hook 1');
        $this->assertFalse($key);
    }

    /**
     * Test.
     */
    public function testMethodPull()
    {
        $items = [
            new HookA(),
            new HookB(),
            new HookC(),
            new HookD(),
        ];
        $collection = new HookCollection($items);

        $this->assertEquals(4, $collection->count());

        // return null if the designated item could not be found
        $this->assertNull($collection->pull('foo_bar'));
        $this->assertEquals(4, $collection->count());

        // return the fallback if the designated item could not be found
        $this->assertEquals('BAR', $collection->pull('foo_bar', 'BAR'));
        $this->assertEquals(4, $collection->count());

        // pull items
        $item = $collection->pull(HookC::class);
        $this->assertInstanceOf(HookC::class, $item);
        $this->assertEquals(3, $collection->count());

        $item = $collection->pull('hook_b');
        $this->assertInstanceOf(HookB::class, $item);
        $this->assertEquals(2, $collection->count());

        $item = $collection->pull(3);
        $this->assertInstanceOf(HookD::class, $item);
        $this->assertEquals(1, $collection->count());
    }

    /**
     * Test.
     */
    public function testMethodGet()
    {
        $items = [
            new HookA(),
            new HookB(),
            new HookC(),
            new HookD(),
        ];
        $collection = new HookCollection($items);

        $this->assertEquals(4, $collection->count());

        // return null if the designated item could not be found
        $this->assertNull($collection->get('foo_bar'));
        $this->assertEquals(4, $collection->count());

        // return the fallback if the designated item could not be found
        $this->assertEquals('BAR', $collection->get('foo_bar', 'BAR'));
        $this->assertEquals(4, $collection->count());

        // pull items
        $item = $collection->get(HookC::class);
        $this->assertInstanceOf(HookC::class, $item);
        $this->assertEquals(4, $collection->count());

        $item = $collection->get('hook_b');
        $this->assertInstanceOf(HookB::class, $item);
        $this->assertEquals(4, $collection->count());

        $item = $collection->get(3);
        $this->assertInstanceOf(HookD::class, $item);
        $this->assertEquals(4, $collection->count());
    }

    /**
     * Test.
     */
    public function testMethodImplode()
    {
        $items = [
            new HookA(),
            new HookB(),
            new HookC(),
            new HookD(),
        ];
        $collection = new HookCollection($items);

        $this->assertEquals(implode(', ', $items), $collection->implode());
        $this->assertEquals(implode('|', $items), $collection->implode('|'));
        $this->assertNull($collection->implode([]));
    }

    /**
     * Test.
     */
    public function testMethodPrepend()
    {
        $items = [
            new HookA(),
            new HookB(),
        ];
        $collection = new HookCollection($items);

        $this->assertEquals(2, $collection->count());

        $collection->prepend(new HookC());
        $this->assertEquals(3, $collection->count());
        $this->assertInstanceOf(HookC::class, $collection[0]);

        $collection->prepend(new HookD());
        $this->assertEquals(4, $collection->count());
        $this->assertInstanceOf(HookD::class, $collection[0]);
        $this->assertInstanceOf(HookC::class, $collection[1]);
        $this->assertInstanceOf(HookA::class, $collection[2]);
        $this->assertInstanceOf(HookB::class, $collection[3]);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The given item is not a valid "' . HookInterface::class . '" instance.');
        $collection->prepend('a additional item');
    }
}
