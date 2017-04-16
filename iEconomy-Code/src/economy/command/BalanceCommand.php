<?php
namespace economy\command;


use economy\iEconomy;
use pocketmine\command\CommandSender;

class BalanceCommand extends EconomyCommand
{

    /** @var iEconomy */
    protected $plugin;

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

        if(isset($args[0])) {
            if (($player = $this->getPlugin()->getServer()->getPlayer($args[0]))) {
                $sender->sendMessage($player->getName()."'s money: " . $this->getPlugin()->getAccount($player)->getMoney());
            } else {
                $sender->sendMessage("Player not found");
            }
        } else {
            $sender->sendMessage("Your money: ".$this->getPlugin()->getAccount($sender->getName())->getMoney());
        }
        return true;
    }

}