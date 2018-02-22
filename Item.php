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

use InvalidArgumentException;
use JsonSerializable;

class Item implements JsonSerializable
{
    protected $attributes = [];
    protected $requiredAttributes = [
        'id',
        'name',
        'quantity',
        'price'
    ];

    public function __construct(array $attributes)
    {
        if ($this->validate($attributes)) {
            $this->attributes = $attributes;
        }
    }

    /**
     * Get an attribute from the container.
     *
     * @param  string $key
     * @param  mixed  $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        if (array_key_exists($key, $this->attributes)) {
            return $this->attributes[$key];
        }

        return $default;
    }

    /**
     * Set the value of an attribute.
     *
     * @param string $key
     * @param mixed  $value
     * @return bool
     */
    public function update(string $key, $value): bool
    {
        if (array_key_exists($key, $this->attributes)) {
            $this->attributes[$key] = $value;
            return true;
        }
        return false;
    }

    public function validate($attributes): bool
    {
        foreach ($this->requiredAttributes as $attribute) {
            if (!array_key_exists($attribute, $attributes)) {
                throw new InvalidArgumentException("The required attribute '{$attribute}' is missing.");
            }
        }

        if (!is_int($attributes['id'])) {
            throw new InvalidArgumentException("ID only accepts integers.");
        }

        return true;
    }

    /**
     * Convert the Item instance to JSON.
     *
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0): string
    {
        return json_encode($this->jsonSerialize(), $options);
    }


    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * Convert the Item instance to an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->attributes;
    }

    public function __get($key)
    {
        return $this->get($key);
    }

    public function __set($key, $value): bool
    {
        return $this->update($key, $value);
    }

    public function __isset($key): bool
    {
        return isset($this->attributes[$key]);
    }
}
