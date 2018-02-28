<?php
/**
 * PHP version 7 package to handle your shopping basket.
 *
 * @author    Andrei Aidinov <aidinov.andrey@gmail.com>
 * @copyright 2018 Aidinov
 * @license   Apache 2.0
 * @version   GIT: <git_id> In development.
 * @link      https://github.com/Aidinov/Basket
 */
namespace Aidinov\Basket;

class Basket
{
    protected $storage;
    
    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }
    
    public function isEmpty(): bool
    {
        return $this->storage->isEmpty();
    }

    public function has(int $id): bool
    {
        return $this->storage->has($id);
    }

    /**
     * Get all the basket content.
     *
     * @return array
     */
    public function contents(): array
    {
        return $this->storage->all();
    }

    /**
     * Get a basket item.
     *
     * @return Item
     */
    public function item(int $id): Item
    {
        return $this->storage->get($id);
    }

    /**
     * Add an Item.
     *
     * @param Item $item
     * @return void
     */
    public function add(Item $item): bool
    {
        if ($this->has($item->id)) {
            $item->quantity = $this->item($item->id)->quantity + $item->quantity;
            return $this->storage->replace($item);
        }
        return $this->storage->add($item);
    }
    
    /**
     * Update a single key for this item.
     *
     * @param int          $id    Identifier of basket item
     * @param string $key  The key attribute to update
     * @param null         $value
     *
     * @return bool
     */
    public function update(int $id, $key, $value = null): bool
    {
        $item = $this->item($id);
        $item->update($key, $value);
        return $this->storage->replace($item);
    }

    public function remove(int $id): bool
    {
        return $this->storage->remove($id);
    }

    public function clear()
    {
        return $this->storage->clear();
    }

    /**
     * Get the total weight of items.
     *
     * @return float
     */
    public function weight(): float
    {
        $weight = 0;
        foreach ($this->contents() as $item) {
            if ($item->weight) {
                $weight += $item->weight;
            }
        }
        
        return $weight;
    }
    
    /**
     * Get the total price.
     *
     * @param boolean $unique   Only unique elements
     * @return integer
     */
    public function total(): float
    {
        $total = 0;
        foreach ($this->contents() as $item) {
            $total += $item->price;
        }

        return $total;
    }
    
    /**
     * Get the total number of items.
     *
     * @param boolean $unique   Only unique elements
     * @return integer
     */
    public function totalItems(bool $unique = false): int
    {
        $quantity = 0;
        foreach ($this->contents() as $item) {
            $quantity += $unique ? 1 : $item->quantity;
        }

        return $quantity;
    }
}
