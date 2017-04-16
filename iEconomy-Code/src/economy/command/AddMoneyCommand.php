<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 17.15.4
 * Time: 19:51
 */

namespace economy\command;


use economy\iEconomy;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class AddMoneyCommand extends EconomyCommand
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

        if(count($args) < 2) {
            $this->sendUsage($sender);
            return false;
        }

        if(!is_numeric($args[1])) {
            $sender->sendMessage("Amount must be numeric");
            return false;
        }

        if(strtolower($args[0]) === strtolower(iEconomy::SERVER_ACCOUNT)) {
            $account = $this->getPlugin()->getAccount(iEconomy::SERVER_ACCOUNT);
        } else {
            if (!($player = $this->getPlugin()->getServer()->getPlayer($args[0]))) {
                $sender->sendMessage("Player not found");
                return true;
            } else {
                $account = $this->getPlugin()->getAccount($player);
            }
        }

        $money = floatval($args[1]);
        $account->addMoney($money);
        if($account->isOnline()) {
            $account->getOwner()->sendMessage(($sender instanceof Player ? $sender->getDisplayName() : $sender->getName()).TextFormat::RESET." has added ".iEconomy::formatMoney($money)." to your account");
        }
        $sender->sendMessage(($sender->getName() === $account->getName() ? "Your" : $account->getName() . "'s")." money set to: ".iEconomy::formatMoney($account->getMoney()));
        return true;
    }

}