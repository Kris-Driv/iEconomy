<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 17.14.4
 * Time: 23:08
 */

namespace economy\transaction;


class Transaction {

    /**
     * Player/server/void from which the money was taken
     * @var string
     */
    protected $from;

    /**
     * Player/server/void to which the money is going to be added
     * @var
     */
    protected $to;

    /**
     * Time this transaction was created
     * @var int
     */
    protected $time;

    /**
     * @var float
     */
    protected $money;

    public function __construct(string $from, string $to, float $money, int $time = null) {
        $this->from = $from;
        $this->to = $to;
        $this->time = $time ? $time : time();
        $this->money = $money;
    }

    public static function get($id, array $data) {

    }

    public function getFrom(): string {
        return $this->from;
    }

    public function getTo(): string {
        return $this->to;
    }

    public function getTime(): int {
        return $this->time;
    }

    public function getMoney(): int {
        return $this->money;
    }

}