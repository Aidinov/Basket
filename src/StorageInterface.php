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

interface StorageInterface
{
    public function isEmpty(): bool;

    public function count(): int;

    public function all(): array;

    public function get(int $id): Item;

    public function has(int $id): bool;

    public function add(Item $item): bool;

    public function replace(int $id, Item $item): bool;

    public function remove(array $keys): bool;

    public function clear();
}
