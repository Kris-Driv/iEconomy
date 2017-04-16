<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 17.15.4
 * Time: 12:30
 */

namespace economy\data;


use economy\Account;
use economy\product\ItemProduct;
use economy\product\ItemType;

class YAMLDataProvider extends DataProvider implements FileBased
{
    use Files;

    public function setup() {
        @mkdir($this->getAccountFolder());
    }

    public function getAccountFolder(): string {
        return $this->getPlugin()->getDataFolder() . "accounts/";
    }


    public function getName(): string
    {
        return "YAML";
    }

    public function getAccount(string $owner): Account
    {
        if($this->accountExists($owner)) {
            $d = @yaml_parse_file($this->getAccountFile($owner, true));
            return new Account($this->getPlugin()->getServer()->getPlayerExact($owner) ?? $owner, $d["money"]);
        }
        return new Account($this->getPlugin()->getServer()->getPlayerExact($owner) ?? $owner, $this->getPlugin()->getDefaultMoney());
    }

    public function saveAccount(Account $account)
    {
        file_put_contents($this->getAccountFile($account->getName(), true), yaml_emit([
            "money" => $account->getMoney()
        ]));
    }

    /**
     * Returns extension including dot
     * @return string
     */
    public function getExtension(): string
    {
        return ".yml";
    }

    /**
     * This function must load all prices into ItemProduct class, pack into array and return
     * @return ItemProduct[]
     */
    public function loadItems(): array
    {
        $data = yaml_parse_file($this->getPlugin()->getDataFolder()."items.yml");
        $items = [];
        foreach ($data as $id => $d) {
            if(strpos($id, "/") !== false) {
                foreach (explode("/", $id) as $id) {
                    $data[$id] = $d;
                }
                continue;
            }
            $each = $d["each"] ?? null;
            $stack = $d["stack"] ?? null;
            $items[$id] = new ItemProduct(ItemProduct::getItemById($id), ($each < 0 ? null : $each), ($stack < 0 ? null : $stack), ItemType::UNDEFINED);
        }
        return $items;
    }

    /**
     * @param ItemProduct[] $items
     */
    public function saveItems(array $items)
    {
        $data = [];
        foreach ($items as $item) {
            $data[$item->getId().(($d = $item->getItem()->getDamage()) > 0 ? ":".$d : "")] = [
              "stack"   => $item->getPricePerStack(),
              "each"    => $item->getPrice(),
              "type"    => $item->getType()
            ];
        }
        file_put_contents($this->getPlugin()->getDataFolder()."items.yml", yaml_emit($data));
    }
}