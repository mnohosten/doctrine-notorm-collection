<?php

namespace Krizos\Doctrine\Colletions\NotORM;

use Doctrine\Common\Collections\Collection;
use NotORM_Result;
use NotORM_Row;
use Closure;

class NotORMCollection implements Collection
{

    /** @var NotORM_Result */
    private $result;
    /** @var callable */
    private $map;

    /**
     * NotORMCollection constructor.
     * @param NotORM_Result $result
     * @param callable $map
     */
    public function __construct(
        NotORM_Result $result,
        callable $map
    )
    {
        $this->result = $result;
        $this->map = $map;
    }


    /**
     * @inheritdoc
     */
    public function add($element)
    {
        return (bool) $this->result->insert($element);
    }


    /**
     * @inheritdoc
     */
    public function clear()
    {
        $this->result = $this->result->where('1', 0);
    }

    /**
     * @inheritdoc
     */
    public function contains($element)
    {
        return $this->result->offsetExists((string)$element);
    }

    /**
     * @inheritdoc
     */
    public function isEmpty()
    {
        return $this->count() === 0;
    }

    /**
     * @inheritdoc
     */
    public function remove($key)
    {
        /** @var NotORM_Row $row */
        $row = $this->result->offsetGet($key);
        if($row) {
            return $row->delete();
        }
        throw new \RuntimeException(
            "Row with key: `{$key}` was not found."
        );
    }

    /**
     * @inheritdoc
     */
    public function removeElement($element)
    {
        $key = (string)$element;
        $this->remove($key);
        $this->result->offsetUnset($key);
        $this->result->rewind();
    }

    /**
     * @inheritdoc
     */
    public function containsKey($key)
    {
        return $this->offsetExists($key);
    }

    /**
     * @inheritdoc
     */
    public function get($key)
    {
        return $this->offsetGet($key);
    }

    /**
     * @inheritdoc
     */
    public function getKeys()
    {
        return array_keys($this->getValues());
    }

    /**
     * @inheritdoc
     */
    public function getValues()
    {
        return iterator_to_array($this->result);
    }

    /**
     * @inheritdoc
     */
    public function set($key, $value)
    {
        return $this->offsetSet($key, $value);
    }

    /**
     * @inheritdoc
     */
    public function toArray()
    {
        return array_map('iterator_to_array', $this->getValues());
    }

    /**
     * @inheritdoc
     */
    public function first()
    {
        $this->result->rewind();
        foreach ($this as $item) {
            return $item;
        }
        throw new \RuntimeException(
            "Collection is empty. It's not possible to obtain first item."
        );
    }

    /**
     * @inheritdoc
     */
    public function last()
    {
        return end($this->getValues());
    }

    /**
     * @inheritdoc
     */
    public function key()
    {
        return $this->result->key();
    }

    /**
     * @inheritdoc
     */
    public function current()
    {
        return
            isset($this->map)
            ? call_user_func($this->map, $this->result->current())
            : $this->result->current();
    }

    /**
     * @inheritdoc
     */
    public function next()
    {
        return $this->result->next();
    }

    /**
     * @inheritdoc
     */
    public function exists(Closure $p)
    {
        foreach ($this as $key=>$item) {
            if($p($key, $item)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function filter(Closure $p)
    {
        $clone = clone $this->result;
        $result = $p($clone);
        $this->assertResultClass($result);
        return new self($result, $this->map);
    }

    /**
     * @inheritdoc
     */
    public function forAll(Closure $p)
    {
        foreach ($this as $key=>$item) {
            if(! $p($key, $item)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function map(Closure $func)
    {
        $clone = clone $this->result;
        return new self($clone, $func);
    }

    private function assertResultClass($result)
    {
        if(!$result instanceof NotORM_Result) {
            throw new \InvalidArgumentException(
                "Mapping clojure must return instance " .
                "of " . NotORM_Result::class
            );
        }
    }

    /**
     * @inheritdoc
     */
    public function partition(Closure $p)
    {
        throw new \LogicException(
            "Not implemnted for NotORM_Result. Use two filter methods instead."
        );
    }

    /**
     * @inheritdoc
     */
    public function indexOf($element)
    {
        return array_search($element, $this->getValues());
    }

    /**
     * @inheritdoc
     */
    public function slice($offset, $length = null)
    {
        $clone = clone $this->result;
        $clone->limit($length, $offset);
        $clone->rewind();
        return new self(
            $clone,
            $this->map
        );
    }

    /**
     * @inheritdoc
     */
    public function getIterator()
    {
        return new NotORMIterator($this->result, $this->map);
    }

    /**
     * @inheritdoc
     */
    public function offsetExists($offset)
    {
        return $this->result->offsetExists($offset);
    }

    /**
     * @inheritdoc
     */
    public function offsetGet($offset)
    {
        return $this->result->offsetGet($offset);
    }

    /**
     * @inheritdoc
     */
    public function offsetSet($offset, $value)
    {
        return $this->result->offsetSet($offset, $value);
    }

    /**
     * @inheritdoc
     */
    public function offsetUnset($offset)
    {
        return $this->result->offsetUnset($offset);
    }

    /**
     * @inheritdoc
     */
    public function count()
    {
        return $this->result->count();
    }

}

