<?php

use Krizos\Doctrine\Colletions\NotORM\NotORMCollection;

class NotORMCollectionTest extends PHPUnit_Framework_TestCase
{

    public function testIndexOf()
    {
        $collection = $this->collection();
        $first = $collection->first();
        $this->assertInstanceOf(NotORM_Row::class, $first);
        $this->assertEquals((string)$first, $collection->indexOf($first));
    }

    public function testFilter()
    {
        $collection = $this->collection();
        $filtered = $collection->filter(function(NotORM_Result $result) {
            return $result->where('1', 0);
        });
        $this->assertGreaterThan(0, $collection->count());
        $this->assertEquals(0, $filtered->count());
    }

    public function testMap()
    {
        $collection = $this->collection();
        $mapped = $collection->map(function($item) {
            $this->assertInstanceOf(NotORM_Row::class, $item);
            return [
                'name' => $item['title'],
            ];
        });
        foreach ($mapped as $item) {
            $this->assertInternalType('array', $item);
            $this->assertCount(1, $item);
        }
    }

    public function testSet()
    {
        $collection = $this->collection();
        $first = $collection->first();
        $this->assertInstanceOf(NotORM_Row::class, $first);
        $collection->set(PHP_INT_MAX, $first);
        $this->assertEquals($first, $collection->get(PHP_INT_MAX));
    }

    public function testGetValues()
    {
        $collection = $this->collection();
        $values = $collection->getValues();
        $this->assertNotEmpty($values);
        foreach ($values as $value) {
            $this->assertInstanceOf(NotORM_Row::class, $value);
            $this->assertNotEmpty($value);
        }
    }

    public function testGetKeys()
    {
        $collection = $this->collection();
        $keys = [];
        foreach ($collection as $key=>$item) $keys[] = $key;
        $this->assertGreaterThan(0, count($keys));
        $this->assertEquals($keys, $collection->getKeys());
    }

    public function testGetByKey()
    {
        $collection = $this->collection();
        $first = $collection->first();
        $this->assertInstanceOf(NotORM_Row::class, $first);
        $last = null;
        foreach ($collection as $item) $last = $item;
        $this->assertInstanceOf(NotORM_Row::class, $last);
        $this->assertNotEquals($first, $last);
        $this->assertEquals($first, $collection->get((string)$first));
        $this->assertEquals($last, $collection->get((string)$last));
    }

    public function testContainsKey()
    {
        $collection = $this->collection();
        $this->add(1, $collection);
        $this->assertGreaterThan(0, $collection->count());
        $firstKey = (string)$collection->first();
        $this->assertTrue($collection->containsKey($firstKey));
        $this->assertFalse($collection->containsKey('a'));
    }

    public function testRemoveElement()
    {
        $collection = $this->collection();
        $this->add(2, $collection);
        $first = $collection->first();
        $this->assertInstanceOf(NotORM_Row::class, $first);
        $count = $collection->count();
        $this->assertGreaterThan(0, $count);
        $collection->removeElement($first);
        $this->assertEquals($count-1, $collection->count());
        foreach ($collection as $item) {
            $this->assertNotEquals((string)$first, (string)$item);
        }
    }

    public function testRemove()
    {
        $collection = $this->collection();
        $this->add(2, $collection);
        $firstKey = (string)$collection->first();
        $collection->remove($firstKey);
        $collection = $this->collection();
        $this->assertGreaterThan(0, $collection->count());
        foreach ($collection as $item) {
            $this->assertFalse($firstKey == (string)$item);
        }
        $this->addToCollection($collection);
    }

    private function add($count, $collection)
    {
        for ($i=0; $i < abs($count); $i++)
            $this->addToCollection($collection);
    }

    public function testIsEmpty()
    {
        $collection = $this->collection();
        $this->assertGreaterThan(0, $collection->count());
        $this->assertFalse($collection->isEmpty());
        $collection->clear();
        $this->assertFalse($collection->isEmpty());
    }

    public function testContains()
    {
        $collection = $this->collection();
        $this->assertGreaterThan(0, $collection->count());
        foreach ($collection as $item) {
            $this->assertTrue($collection->contains($item));
        }
    }

    public function testCount()
    {
        $collection = $this->collection();
        $this->assertGreaterThan(0, $collection->count());
        $collection->clear();
        $this->assertEquals(0, $collection->count());
    }

    public function testAdd()
    {
        $collection = $this->collection();
        $this->assertTrue($this->addToCollection($collection));
    }

    private function collection()
    {
        return new NotORMCollection(
            $this->notorm()->application(),
            function($item) { return $item; }
        );
    }

    private function notorm()
    {
        static $pdo = null;
        if(!isset($pdo)) {
            $pdo = new \PDO(
                'mysql:host=localhost;dbname=application',
                'root',
                'root'
            );
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        $notORM = new NotORM($pdo);
        $notORM->debug = function ($sql, $args) {
            echo $sql . PHP_EOL;
        };
        return $notORM;
    }

    /**
     * @param $collection
     * @return bool
     */
    private function addToCollection($collection)
    {
        $faker = \Faker\Factory::create('cz_CZ');
        $title = $faker->words(5, true);
        $new = [
            'author_id' => 13,
            'maintainer_id' => 13,
            'title' => $title,
            'web' => $faker->url,
            'slogan' => $faker->sentence(6),
        ];
        $this->assertTrue($collection->add($new));
        $found = false;
        foreach ($collection as $item) {
            if ($item['title'] == $title) $found = true;
        }
        return $found;
    }

}

