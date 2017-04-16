<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 17.15.4
 * Time: 17:41
 */

namespace economy\data;


interface FileBased
{

    /**
     * Returns extension including dot
     * @return string
     */
    public function getExtension(): string;

}