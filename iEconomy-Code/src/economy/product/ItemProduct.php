<?php
namespace economy\product;


use economy\iEconomy;
use pocketmine\item\Item;

class ItemProduct extends Product
{

    /** @var float */
    protected $price, $pricePerStack;

    /** @var Item */
    protected $item;

    /** @var int */
    protected $type;

    /**
     * ItemProduct constructor.
     * @param Item $item
     * @param float $price
     * @param int $type
     * @param float $pricePerStack
     */
    public function __construct(Item $item, float $price = null, float $pricePerStack = null, int $type = ItemType::UNDEFINED)
    {
        parent::__construct($item->getId().(($d = $item->getDamage()) > 0 ? ":".$d : ""), $price, $item->getCount());
        $this->item = $item;
        $this->price = $price ? $price : iEconomy::getInstance()->getPrice($item);
        $this->pricePerStack = $pricePerStack ? $pricePerStack : $price * $item->getMaxStackSize();
        $this->type = $type;
    }

    public static function getItemById($id): Item
    {
        $meta = 0;
        if(strpos($id, ":") !== false) {
            list($id, $meta) = explode(":", $id);
            if($meta === "*" or $meta === "@") $meta = 0;
            $meta = (int) $meta;
        }
        return Item::get($id, $meta);
    }

    public static function getProductId(Item $item)
    {
        return $item->getId().(($d = $item->getDamage()) > 0 ? ":".$d : "");
    }

    public function getName(): string
    {
        return $this->item->getName();
    }

    public function getDisplayName(): string
    {
        return $this->getName()." x".$this->getCount();
    }

    public function getCount(): int
    {
        return $this->item->getCount();
    }

    public function setCount(int $count)
    {
        $this->item->setCount($count);
    }

    public function getItem(): Item {
        return $this->item;
    }

    public function getPricePerStack(): float {
        return $this->pricePerStack;
    }

    public function setPricePerStack(float $price) {
        $this->pricePerStack = $price;
    }

    /**
     * TODO: Make this more complex
     * @param int $count
     * @return float
     */
    public function getPrice(int $count = null): float
    {
        $count = $count ?? $this->getCount();
        if($count >= $this->item->getMaxStackSize()) {
            $stacks = floor($count / $this->item->getMaxStackSize());
            $ones = $count - ($this->item->getMaxStackSize() * $stacks);
            return $stacks * $this->getPricePerStack() + $ones * $this->price;
        } else {
            return $this->price * $count;
        }
    }

    public function getType(): int {
        return $this->type;
    }

}