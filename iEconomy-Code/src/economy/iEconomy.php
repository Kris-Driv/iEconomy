<?php
namespace economy;

use economy\command\AddMoneyCommand;
use economy\command\BalanceCommand;
use economy\command\PriceCommand;
use economy\command\SetMoneyCommand;
use economy\command\TakeMoneyCommand;
use economy\data\DataProvider;
use economy\data\YAMLDataProvider;
use economy\product\ItemProduct;
use economy\product\Product;
use pocketmine\command\CommandSender;
use pocketmine\inventory\ShapedRecipe;
use pocketmine\inventory\ShapelessRecipe;
use pocketmine\IPlayer;
use pocketmine\item\Item;
use pocketmine\plugin\PluginBase;

class iEconomy extends PluginBase
{
    const DEFAULT_MONEY = 100.00;
    const SERVER_ACCOUNT = "CONSOLE";

    /** @var iEconomy */
    private static $instance;

    public static function getInstance(): iEconomy
    {
        return self::$instance;
    }

    /** @var DataProvider */
    protected $dataProvider;

    /**
     * Saved with this format: id.Type (eg. Item stone = 1.Item)
     * @var Product[]
     */
    protected $products = [];

    public static function formatMoney(float $money)
    {
        return str_replace(["{MONEY}", "{SYMBOL}"], [$money, self::getInstance()->getConfig()->get("currency-symbol", "$")], self::getInstance()->getConfig()->get("money-format", "{SYMBOL}{MONEY}"));
    }

    public function onLoad()
    {
        @mkdir($this->getDataFolder());
        self::$instance = $this;
        $this->saveDefaultConfig();
        $this->saveResource("items.yml");
    }

    public function onEnable()
    {
        # 1) Setup data provider
        $this->setupDataProvider();
        if(!$this->getDataProvider() instanceof DataProvider) {
            $this->getLogger()->error("No valid data provider. Disabling...");
            goto stop;
        } else {
            $this->getLogger()->info("Data provider: ".$this->getDataProvider()->getName());
        }
        # 2) Load all products
        $this->loadProducts();

        # 3) Register commands
        $this->registerCommands();

        # 4) Register event listeners
        //$this->registerListeners();

        return;
        stop:
            $this->getServer()->getPluginManager()->disablePlugin($this);
    }

    public function onDisable()
    {
        # 1) Save data
        $this->saveData();

        # 2) Close data provider
        $this->closeDataProvider();
    }

    public function getDataProvider() {
        return $this->dataProvider;
    }

    public function setDataProvider(DataProvider $provider) {
        $this->dataProvider = $provider;
    }

    /**
     * Initializes DataProvider class
     */
    private function setupDataProvider()
    {
        switch (strtolower(trim($this->getConfig()->get("data-provider", "yaml")))) {
            case "yml":
            case "yaml":
            default:
                $this->setDataProvider(new YAMLDataProvider($this));
                break;
        }
    }

    /**
     * Fetch prices and other item data from database
     */
    private function loadProducts()
    {
        // Items
        $items = $this->products = $this->getDataProvider()->loadItems();
        foreach ($items as $id => $item) {
            if(strpos($id, ":") !== false) {
                $meta = explode(":", $id)[1];
                // TODO
            }
            $this->products[$id.".Item"] = $item;
        }
        // Enchantment
        // TODO
    }

    /**
     * Registers iEconomy commands
     */
    private function registerCommands()
    {
        $commands = [
            new BalanceCommand($this, "balance", "See your money", "/balance [player]", ["mymoney", "money", "bal"]),
            new SetMoneyCommand($this, "setmoney", "Set money", "/setmoney <player> <amount>"),
            new AddMoneyCommand($this, "addmoney", "Add money", "/addmoney <player> <amount>"),
            new TakeMoneyCommand($this, "takemoney", "Take money", "/takemoney <player> <amount>"),
            new PriceCommand($this, "price", "Set, or show price of a product", "/price <id> <type> [price] [pricePerStack]")
        ];
        $this->getServer()->getCommandMap()->registerAll("i-economy", $commands);
    }

    /**
     * Registers event listeners
     */
    private function registerListeners() {

    }

    public function saveData() {
        if($this->dataProvider instanceof DataProvider) {
            $this->dataProvider->saveItems($this->getProductsByType("Item"));
        }
    }

    private function closeDataProvider()
    {
        if($this->dataProvider instanceof DataProvider) {
            $this->dataProvider->close();
        }
    }

    // -------------------------------------- //
    // API METHODS                            //
    // -------------------------------------- //

    public function getPrice(Item $item): int
    {
        /** @var ItemProduct $p */
        if(($p = $this->getItemProduct($item))) {
            return $p->getPrice($item->getCount());
        }
        if(!empty($r = $this->getServer()->getCraftingManager()->getRecipesByResult($item))) {
            $r = $r[0];
            if($r instanceof ShapedRecipe || $r instanceof ShapelessRecipe) {
                $ingredients = $r->getIngredientList();
                $p = 0;
                foreach ($ingredients as $ingredient) {
                    $p += $this->getPrice($ingredient);
                }
                return $p;
            }
        }
        return 0;
    }

    /**
     * @param Item $item
     * @return Product|null
     */
    public function getItemProduct(Item $item)
    {
        return $this->getProduct($id = $item->getId().(($d = $item->getDamage()) > 0 ? ":".$d : ""), "Item");
    }

    /**
     * ID is Product id. for ItemProduct it's item id and metadata after colon if > 0
     * Type is Product type eg. ItemProduct = "Item", EnchantmentProduct = "Enchantment"
     *
     * @param string $id
     * @param string $type
     * @return Product|null
     */
    public function getProduct(string $id, string $type)
    {
        if(isset($this->products[$id.".".$type])) return $this->products[$id.".".$type];
        return null;
    }

    public function addProduct(Product $product, string $type, bool $force = true): bool
    {
        if($this->getProduct($id = $product->getId(), $type) && !$force) return false;
        $this->products[$id.".".$type] = $product;
        return true;
    }

    /**
     * @param CommandSender|IPlayer|string $owner
     * @return Account
     */
    public function getAccount($owner): Account {
        return $this->getDataProvider()->getAccount($owner instanceof CommandSender ? $owner->getName() : $owner);
    }

    public function getDefaultMoney(): float {
        return (float) $this->getConfig()->get("default-money", self::DEFAULT_MONEY);
    }

    private function getProductsByType(string $type): array
    {
        $r = [];
        foreach($this->products as $k => $p) {
            if(strpos($k, ".".ucfirst($type)) !== false) $r[] = $p;
        }
        return $r;
    }

}