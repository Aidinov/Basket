<?php

namespace Tests;

use Aidinov\Basket\Basket;
use Aidinov\Basket\Item;
use Aidinov\Basket\Storage\Runtime as RuntimeStorage;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;

class BasketTest extends TestCase
{
    private $basket;

    public function setUp()
    {
        $this->basket = new Basket(new RuntimeStorage);
    }

    public function tearDown()
    {
        $this->basket->clear();
    }

    private function newItem()
    {
        $id = rand(1, 100);
        $weight = rand(200, 300);
        $quantity = rand(1, 10);
        $price = rand(1, 10) * 100;
        $item = [
            'id'       => $id,
            'name'     => 'Foo'.$id,
            'price'    => $price,
            'quantity' => $quantity,
            'weight'   => $weight,
        ];
        $item = new Item($item);
        return $item;
    }

    public function testAdditionItem()
    {
        $this->assertTrue($this->basket->add($this->newItem()));
    }

    public function testEmpty()
    {
        $this->assertTrue($this->basket->isEmpty());
        $this->basket->add($this->newItem());
        $this->assertFalse($this->basket->isEmpty());
    }

    public function testHas()
    {
        $item = $this->newItem();
        $this->assertFalse($this->basket->has($item->id));
        $this->basket->add($item);
        $this->assertTrue($this->basket->has($item->id));
    }

    public function testGetItem()
    {
        $item = $this->newItem();
        $this->basket->add($item);
        $this->assertEquals($item->id, $this->basket->item($item->id)->id);
    }

    public function testUpdate()
    {
        $item = $this->newItem();
        $this->basket->add($item);
        $this->basket->update($item->id, 'quantity', 150);
        $this->assertEquals(150, $this->basket->item($item->id)->quantity);
    }

    public function testReplaceQuantity()
    {
        $item = $this->newItem();
        $this->basket->add($item);
        $GQuantity = $item->quantity + 20;
        $item = clone $item;
        $item->quantity = 20;
        $this->basket->add($item);
        $this->assertEquals($GQuantity, $this->basket->totalItems());
    }

    public function testTotalItemsAndRemove()
    {
        list($item1, $item2) = [$this->newItem(), $this->newItem()];
        $this->basket->add($item1);
        $this->basket->add($item2);
        $GQuantity = $item1->quantity + $item2->quantity;
        $this->assertEquals($GQuantity, $this->basket->totalItems());
        $this->assertEquals(2, $this->basket->totalItems(true));
        $this->assertTrue($this->basket->remove($item2->id));
        $this->assertEquals(1, $this->basket->totalItems(true));
    }

    public function testWeight()
    {
        list($item1, $item2) = [$this->newItem(), $this->newItem()];
        $GWeight = $item1->weight + $item2->weight;
        $this->basket->add($item1);
        $this->basket->add($item2);
        $this->assertEquals($GWeight, $this->basket->weight());
    }

    public function testTotal()
    {
        list($item1, $item2) = [$this->newItem(), $this->newItem()];
        $GPrice = $item1->price + $item2->price;
        $this->basket->add($item1);
        $this->basket->add($item2);
        $this->assertEquals($GPrice, $this->basket->total());
    }
}
