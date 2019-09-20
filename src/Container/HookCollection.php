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

namespace DMX\Application\Pipeline\Container;

use Illuminate\Support\Collection;
use DMX\Application\Pipeline\Contracts\HookInterface;
use DMX\Application\Pipeline\Exceptions\HookNotFoundException;

/**
 * Class HookCollection.
 *
 * @method array|HookInterface[] all()
 */
class HookCollection extends Collection
{
    /**
     * {@in.
     *
     * @var array|HookInterface[]
     */
    protected $items = [];

    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException if the one or more items are not valid HookInterface instances.
     */
    protected function getArrayableItems($items): array
    {
        $items = parent::getArrayableItems($items);
        foreach ($items as $key => $item) {
            if (!is_object($item) || !($item instanceof HookInterface)) {
                throw new \InvalidArgumentException('One or more of the given items are not valid "' . HookInterface::class . '" instances.');
            }
        }

        return $items;
    }

    /**
     * @param string $value
     * @param bool   $strict
     *
     * @return int|false
     */
    public function search($value, $strict = false)
    {
        $value = (string) $value;
        foreach ($this->items as $key => $item) {
            if ($item->name() === $value || get_class($item) === $value) {
                return (int) $key;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     *
     * @param HookInterface $item
     *
     * @return Collection|HookInterface[]|self
     *
     * @throws \InvalidArgumentException if the given value is not a valid HookInterface instance.
     */
    public function push($item): self
    {
        if (!is_object($item) || !($item instanceof HookInterface)) {
            throw new \InvalidArgumentException('The given item is not a valid "' . HookInterface::class . '" instance.');
        }

        return parent::push($item);
    }

    /**
     * @param mixed $key
     * @param null  $default
     *
     * @return HookInterface|null
     */
    public function pull($key, $default = null)
    {
        if (is_integer($key)) {
            return parent::pull($key, $default);
        }

        $key = $this->search((string) $key);
        if ($key === false) {
            return $default;
        }

        return parent::pull($key, $default);
    }

    /**
     * Alias for push.
     *
     * @see push()
     *
     * @param HookInterface $item
     *
     * @return Collection|HookInterface[]|self
     * @codeCoverageIgnore
     */
    public function add($item): self
    {
        return $this->push($item);
    }

    /**
     * {@inheritdoc}
     *
     * An object-item could not be matched with any relational operator except '=', therefore
     * this variant of the function ignores all parameters except the $key parameter.
     *
     * The $key parameter will always be matched with the name of the object-items (hooks) not with their array key!
     */
    public function contains($key, $operator = null, $value = null): bool
    {
        if ($key instanceof HookInterface) {
            return parent::contains(function (HookInterface $item) use ($key) {
                return $item->name() === $key->name();
            });
        }

        return parent::contains(function (HookInterface $item) use ($key) {
            return $item->name() === (string) $key || get_class($item) === (string) $key;
        });
    }

    /**
     * Find the first object in the collection matched with the key.
     *
     * @param string $name
     *
     * @return HookInterface|null
     */
    public function find(string $name): ?HookInterface
    {
        if ($this->isEmpty()) {
            return null;
        }

        return $this->filter(function (HookInterface $item) use ($name) {
            return $item->name() === $name || get_class($item) === $name;
        })->first();
    }

    /**
     * Find the first object in the collection matched with the key/name or throw an exception.
     *
     * @param string $name
     *
     * @return HookInterface
     *
     * @throws HookNotFoundException if no item could be found
     *
     * @see find()
     */
    public function findOrFail(string $name): HookInterface
    {
        $item = $this->find($name);
        if ($item === null) {
            throw new HookNotFoundException('No hook found matched with name "' . $name . '".');
        }

        return $item;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($key)
    {
        if (is_integer($key)) {
            return parent::offsetExists((int) $key);
        }

        if (is_string($key)) {
            foreach ($this->items as  $item) {
                if ($item->name() === $key || get_class($item) === $key) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($key)
    {
        if (is_integer($key)) {
            return parent::offsetGet((int) $key);
        }

        if (is_string($key)) {
            foreach ($this->items as $i => $item) {
                if ($item->name() === $key || get_class($item) === $key) {
                    return $this->items[$i];
                }
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($key)
    {
        if (is_integer($key)) {
            parent::offsetUnset((int) $key);
        } elseif (is_string($key)) {
            foreach ($this->items as $i => $item) {
                if ($item->name() === (string) $key) {
                    unset($this->items[$i]);
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     *
     * @return HookInterface|null
     */
    public function get($key, $default = null)
    {
        if (is_integer($key)) {
            return parent::get($key, $default);
        }

        if (is_string($key)) {
            foreach ($this->items as  $item) {
                if ($item->name() === $key || get_class($item) === $key) {
                    return $item;
                }
            }
        }

        return value($default);
    }

    /**
     * @param string $value
     * @param null   $glue
     *
     * @return string|null
     */
    public function implode($value = ', ', $glue = null)
    {
        if (is_array($value) || is_object($value)) {
            return null;
        }

        $names = [];
        foreach ($this->items as $item) {
            $names[] = $item->toString();
        }

        return implode($value, $names);
    }

    /**
     * @param mixed $value
     * @param null  $key
     *
     * @return Collection
     */
    public function prepend($value, $key = null)
    {
        if (!is_object($value) || !($value instanceof HookInterface)) {
            throw new \InvalidArgumentException('The given item is not a valid "' . HookInterface::class . '" instance.');
        }

        return parent::prepend($value, $key);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \BadMethodCallException because this method is not workable for this typ of collection!
     */
    public static function times($number, callable $callback = null)
    {
        throw new \BadMethodCallException('This method is not realizable for this collection!');
    }

    /**
     * {@inheritdoc}
     *
     * @throws \BadMethodCallException because this method is not workable for this typ of collection!
     */
    public function avg($callback = null)
    {
        throw new \BadMethodCallException('This method is not realizable for this collection!');
    }

    /**
     * {@inheritdoc}
     *
     * @throws \BadMethodCallException because this method is not workable for this typ of collection!
     */
    public function median($key = null)
    {
        throw new \BadMethodCallException('This method is not realizable for this collection!');
    }

    /**
     * {@inheritdoc}
     *
     * @throws \BadMethodCallException because this method is not workable for this typ of collection!
     */
    public function mode($key = null)
    {
        throw new \BadMethodCallException('This method is not realizable for this collection!');
    }

    /**
     * {@inheritdoc}
     *
     * @throws \BadMethodCallException because this method is not workable for this typ of collection!
     */
    public function collapse()
    {
        throw new \BadMethodCallException('This method is not realizable for this collection!');
    }

    /**
     * {@inheritdoc}
     *
     * @throws \BadMethodCallException because this method is not workable for this typ of collection!
     */
    public function flip()
    {
        throw new \BadMethodCallException('This method is not realizable for this collection!');
    }

    /**
     * {@inheritdoc}
     *
     * @throws \BadMethodCallException because this method is not workable for this typ of collection!
     */
    public function groupBy($groupBy, $preserveKeys = false)
    {
        throw new \BadMethodCallException('This method is not realizable for this collection!');
    }

    /**
     * {@inheritdoc}
     *
     * @throws \BadMethodCallException because this method is not workable for this typ of collection!
     */
    public function except($keys)
    {
        throw new \BadMethodCallException('This method is not realizable for this collection!');
    }

    /**
     * {@inheritdoc}
     *
     * @throws \BadMethodCallException because this method is not workable for this typ of collection!
     */
    public function flatten($depth = INF)
    {
        throw new \BadMethodCallException('This method is not realizable for this collection!');
    }

    /**
     * {@inheritdoc}
     *
     * @throws \BadMethodCallException because this method is not workable for this typ of collection!
     */
    public function keyBy($keyBy)
    {
        throw new \BadMethodCallException('This method is not realizable for this collection!');
    }

    /**
     * {@inheritdoc}
     *
     * @throws \BadMethodCallException because this method is not workable for this typ of collection!
     */
    public function join($glue, $finalGlue = '')
    {
        throw new \BadMethodCallException('This method is not realizable for this collection!');
    }

    /**
     * {@inheritdoc}
     *
     * @throws \BadMethodCallException because this method is not workable for this typ of collection!
     */
    public function mapToDictionary(callable $callback)
    {
        throw new \BadMethodCallException('This method is not realizable for this collection!');
    }

    /**
     * {@inheritdoc}
     *
     * @throws \BadMethodCallException because this method is not workable for this typ of collection!
     */
    public function mapWithKeys(callable $callback)
    {
        throw new \BadMethodCallException('This method is not realizable for this collection!');
    }

    /**
     * {@inheritdoc}
     *
     * @throws \BadMethodCallException because this method is not workable for this typ of collection!
     */
    public function mergeRecursive($items)
    {
        throw new \BadMethodCallException('This method is not realizable for this collection!');
    }

    /**
     * {@inheritdoc}
     *
     * @throws \BadMethodCallException because this method is not workable for this typ of collection!
     */
    public function nth($step, $offset = 0)
    {
        throw new \BadMethodCallException('This method is not realizable for this collection!');
    }

    /**
     * {@inheritdoc}
     *
     * @throws \BadMethodCallException because this method is not workable for this typ of collection!
     */
    public function replaceRecursive($items)
    {
        throw new \BadMethodCallException('This method is not realizable for this collection!');
    }

    /**
     * {@inheritdoc}
     *
     * @throws \BadMethodCallException because this method is not workable for this typ of collection!
     */
    public function splice($offset, $length = null, $replacement = [])
    {
        throw new \BadMethodCallException('This method is not realizable for this collection!');
    }

    /**
     * {@inheritdoc}
     *
     * @throws \BadMethodCallException because this method is not workable for this typ of collection!
     */
    public function split($numberOfGroups)
    {
        throw new \BadMethodCallException('This method is not realizable for this collection!');
    }

    /**
     * {@inheritdoc}
     *
     * @throws \BadMethodCallException because this method is not workable for this typ of collection!
     */
    public function chunk($size)
    {
        throw new \BadMethodCallException('This method is not realizable for this collection!');
    }

    /**
     * {@inheritdoc}
     *
     * @throws \BadMethodCallException because this method is not workable for this typ of collection!
     */
    public function take($limit)
    {
        throw new \BadMethodCallException('This method is not realizable for this collection!');
    }

    /**
     * {@inheritdoc}
     *
     * @throws \BadMethodCallException because this method is not workable for this typ of collection!
     */
    public function transform(callable $callback)
    {
        throw new \BadMethodCallException('This method is not realizable for this collection!');
    }

    /**
     * {@inheritdoc}
     *
     * @throws \BadMethodCallException because this method is not workable for this typ of collection!
     */
    public function zip($items)
    {
        throw new \BadMethodCallException('This method is not realizable for this collection!');
    }

    /**
     * {@inheritdoc}
     *
     * @throws \BadMethodCallException because this method is not workable for this typ of collection!
     */
    public function pad($size, $value)
    {
        throw new \BadMethodCallException('This method is not realizable for this collection!');
    }
}
