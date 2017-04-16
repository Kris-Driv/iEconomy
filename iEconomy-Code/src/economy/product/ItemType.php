<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 17.15.4
 * Time: 00:17
 */

namespace economy\product;


final class ItemType
{

    const UNDEFINED = -1;
    const ORES = 1;
    const BLOCKS_RAW = 2;
    const BLOCKS_CRAFTED = 3;
    const WOOD = 4;
    const PLANTS = 4;
    const FOOD = 5;
    const FARMING = 5;
    const DROPS = 6;
    const DECORATIVE = 7;
    const MECHANISMS = 8;
    const MISC = 9;
    const DYES = 10;
    const CRAFTING = 10;
    const BREWING = 11;
    const TOOLS = 12;
    const WEAPONS = 12;

    private function __construct() {}

}