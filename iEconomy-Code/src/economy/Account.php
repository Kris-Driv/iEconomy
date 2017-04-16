<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 17.15.4
 * Time: 17:27
 */

namespace economy;

use pocketmine\command\ConsoleCommandSender;
use pocketmine\Player;

class Account
{

    /** @var float */
    protected $money = 0.00;

    /** @var string|Player */
    protected $owner;

    public function __construct($owner, float $money) {
        $this->owner = $owner;
        $this->money = $money;
        if($this->isServer()) {
            $this->owner = new ConsoleCommandSender();
        }
    }

    public function getMoney(): float {
        return (float) $this->money;
    }

    public function addMoney(float $money) {
        $this->money += $money;
        $this->save();
    }

    public function takeMoney(float $money) {
        $this->addMoney(-$money);
    }

    public function setMoney(float $money) {
        $this->money = $money;
        $this->save();
    }

    public function getOwner() {
        return $this->owner;
    }

    public function getName(): string {
        if(is_string($o = $this->getOwner())) return $o;
        if(method_exists($o, "getName")) {
            return $o->getName();
        }
        return null;
    }

    public function isServer(): bool {
        if($this->owner instanceof ConsoleCommandSender) return true;
        if($this->owner === strtolower(iEconomy::SERVER_ACCOUNT)) return true;
        return false;
    }

    public function save() {
        iEconomy::getInstance()->getDataProvider()->saveAccount($this);
    }

    public function isOnline(): bool {
        return is_object($this->getOwner());
    }

}