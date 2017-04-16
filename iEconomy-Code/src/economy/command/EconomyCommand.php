<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 17.15.4
 * Time: 18:22
 */

namespace economy\command;


use economy\iEconomy;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;

abstract class EconomyCommand extends Command implements PluginIdentifiableCommand
{

    /** @var iEconomy */
    protected $plugin;

    public function __construct(iEconomy $plugin, $name, $description = "", $usageMessage = null, $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
        $this->plugin = $plugin;
    }

    public function getPlugin(): iEconomy
    {
        return $this->plugin;
    }


    public function sendUsage(CommandSender $sender) {
        $sender->sendMessage("Usage: ".$this->getUsage());
    }

}