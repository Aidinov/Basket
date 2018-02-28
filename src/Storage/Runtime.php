<?php

namespace Aidinov\Basket\Storage;

use Aidinov\Basket\Item;
use Aidinov\Basket\StorageInterface;

class Runtime implements StorageInterface
{
    protected static $storage = [];

    public function isEmpty(): bool
    {
        return !(bool) count(self::$storage);
    }

    public function count(): int
    {
        return count(self::$storage);
    }

    public function all(): array
    {
        $all = [];
        foreach (self::$storage as $attributes) {
            $all[] = new Item(json_decode($attributes, true));
        }
        return $all;
    }

    public function get(int $id): Item
    {
        if ($this->has($id)) {
            return new Item(json_decode(self::$storage[$id], true));
        }
    }

    public function has(int $id): bool
    {
        return isset(self::$storage[$id]);
    }

    public function add(Item $item): bool
    {
        if ($this->has($item->id)) {
            return false;
        }

        self::$storage[$item->id] = $item->toJson();
        return true;
    }

    public function replace(Item $item): bool
    {
        if ($this->has($item->id)) {
            self::$storage[$item->id] = $item->toJson();
            return true;
        }

        return false;
    }

    public function remove(int $id): bool
    {
        if ($this->has($id)) {
            unset(self::$storage[$id]);
            return true;
        }

        return false;
    }

    public function clear()
    {
        self::$storage = [];
    }

    public function save()
    {
        // save data somewhere
    }
}
