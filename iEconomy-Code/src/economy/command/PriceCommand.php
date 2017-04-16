<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 17.15.4
 * Time: 20:24
 */

namespace economy\command;


use economy\iEconomy;
use economy\product\ItemProduct;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;

class PriceCommand extends EconomyCommand
{


    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param string[] $args
     *
     * @return mixed
     */
    public function execute(CommandSender $sender, $commandLabel, array $args)
    {
        if(!$this->testPermission($sender)) {
            return true;
        }

        if(count($args) < 1) {
            $this->sendUsage($sender);
            return false;
        }
        $type = ucfirst($args[1] ?? "Item");
        $id = $args[0];
        if($type === "Item") {
            if(!is_numeric($id)) {
                $item = Item::fromString($id);
                if(strpos($id, ":") !== false) {
                    $item->setDamage((int) explode(":", $id)[1]);
                }
                $id = $item->getId();
                if($id === 0) {
                    $sender->sendMessage("Item by name '$args[0]' can't be found");
                    return false;
                }
                $id = ItemProduct::getProductId($item);
            }
        }

        $product = $this->getPlugin()->getProduct($id, $type);
        if(!$product) {
            $sender->sendMessage("Product: $id.$type was not found");
            return false;
        }
        if(count($args) >= 2) {
            if($type === ucfirst($args[1])) {
                $prices = array_slice($args, 2);
            } else {
                $prices = array_slice($args, 1);
            }
            /** @var ItemProduct $product */
            $product->setPrice((float) $prices[0] ?? (float) iEconomy::getInstance()->getPrice($product->getItem()));
            $product->setPricePerStack($prices[1] ?? (float) $product->getPrice() * $product->getItem()->getMaxStackSize());
        } else {
            if ($product instanceof ItemProduct) {
                $sender->sendMessage("----- " . $product->getName() . " -----");
                $sender->sendMessage("Price per item: " . $product->getPrice());
                $sender->sendMessage("Price per stack: " . $product->getPricePerStack());
            } else {
                $sender->sendMessage("Unknown product");
            }
        }

        return true;
    }

}