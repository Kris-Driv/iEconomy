<?php
namespace economy\data;


use economy\Account;
use economy\iEconomy;
use economy\product\ItemProduct;
use pocketmine\entity\Item;

abstract class DataProvider
{

    /** @var iEconomy */
    private $plugin;

    public function __construct(iEconomy $plugin)
    {
        $this->plugin = $plugin;
        $this->setup();
    }

    public function setup()
    {
        # Dummy function
    }

    public function getPlugin(): iEconomy {
        return $this->plugin;
    }

    public function close()
    {
        # Dummy function
    }



    public abstract function getName(): string;

    public abstract function getAccount(string $owner): Account;

    public abstract function accountExists(string $owner): bool;

    public abstract function saveAccount(Account $account);

    /**
     * This function must load all prices into ItemProduct class, pack into array and return
     * @return ItemProduct[]
     */
    public abstract function loadItems(): array;

    /**
     * @param ItemProduct[] $items
     */
    public abstract function saveItems(array $items);

}