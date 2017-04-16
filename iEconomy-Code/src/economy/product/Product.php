<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 17.14.4
 * Time: 23:14
 */

namespace economy\product;


abstract class Product
{

    /**
     * Used to track purchases
     * @var string|int
     */
    protected $id;

    /** @var float */
    protected $price;

    /** @var int */
    protected $count;

    /**
     * @param int $id
     * @param float $price
     * @param int $count
     */
    public function __construct($id, float $price, int $count = 1)
    {
        $this->id = $id;
        $this->price = $price;
        $this->count = $count;
    }

    public function getPrice(): float {
        return $this->price;
    }

    public function setPrice(float $price) {
        $this->price = $price;
    }

    public function getCount() {
        return $this->count;
    }

    public function setCount(int $count) {
        $this->count = $count;
    }

    public abstract function getName(): string;

    /**
     * This function can be used to format the name for signs to show metadata (eg. count)
     * @return string
     */
    public function getDisplayName(): string {
        return $this->getName();
    }

    public function getId() {
        return $this->id;
    }

    public abstract function give(Player $player): bool;

}