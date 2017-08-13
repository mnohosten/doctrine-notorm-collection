<?php
namespace Krizos\Doctrine\Colletions\NotORM;

use NotORM_Result;

class NotORMIterator implements \Iterator
{
    /** @var NotORM_Result */
    private $result;
    /** @var Closure */
    private $map;

    /**
     * NotORMIterator constructor.
     * @param NotORM_Result $result
     * @param callable $map
     */
    public function __construct(NotORM_Result $result, callable $map)
    {
        $this->result = $result;
        $this->map = $map;
    }


    public function current()
    {
        return call_user_func($this->map, $this->result->current());
    }

    public function next()
    {
        return $this->result->next();
    }

    public function key()
    {
        return $this->result->key();
    }

    public function valid()
    {
        return $this->result->valid();
    }

    public function rewind()
    {
        return $this->result->rewind();
    }

}
