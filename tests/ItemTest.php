<?php

namespace Tests;

use Aidinov\Basket\Item;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;

class ItemTest extends TestCase
{
    private $item;

    public function setUp()
    {
    }

    public function tearDown()
    {
        $this->item = null;
    }

    private function newGeneralItem()
    {
        $id = rand(1, 100);
        $weight = rand(200, 300);
        $quantity = rand(1, 10);
        $item = [
            'id'       => $id,
            'name'     => 'Foo',
            'price'    => 100,
            'quantity' => $quantity,
            'weight'   => $weight,
        ];
        $this->item = new Item($item);
        return $item;
    }

    public function testValidateRequaredAttributes()
    {
        $this->expectException(InvalidArgumentException::class);
        $item = new Item([]);
    }

    public function testGetAttribute()
    {
        $attributes = $this->newGeneralItem();
        $this->assertEquals($attributes['id'], $this->item->id);
        $this->assertEquals($attributes['quantity'], $this->item->get('quantity'));
    }

    public function testGetAttributeDefaultValue()
    {
        $attributes = $this->newGeneralItem();
        $this->assertEquals("DEFAULT", $this->item->get('image', 'DEFAULT'));
    }

    public function testUpdateItemID()
    {
        $this->expectException(InvalidArgumentException::class);
        $attributes = $this->newGeneralItem();
        $this->item->update('id', 23);
    }

    public function testUpdateItem()
    {
        $attributes = $this->newGeneralItem();
        $this->assertTrue($this->item->update('quantity', 15));
        $this->assertFalse($this->item->update('image', 'http..'));
        $this->assertEquals(15, $this->item->quantity);
    }

    public function testItemToJson()
    {
        $attributes = $this->newGeneralItem();
        $this->assertEquals(json_encode($attributes), $this->item->toJson());
    }
}
