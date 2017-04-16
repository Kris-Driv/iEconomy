<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 17.14.4
 * Time: 23:04
 */

namespace economy\purchase;


class Filter {

    /**
     * @var string
     */
    public $from;

    /**
     * @var string
     */
    public $to;

    /**
     * @var float
     */
    public $priceMinimum;

    /**
     * @var float
     */
    public $priceMaximum;

    /** @var int */
    public $timeFrom;

    /**
     * @var int
     */
    public $timeTo;

    /**
     * @var bool
     */
    public $purchase;

}