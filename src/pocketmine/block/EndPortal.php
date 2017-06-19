<?php

/*
 *
 *    _______                                _
 *   |__   __|                              | |
 *      | | ___  ___ ___  ___ _ __ __ _  ___| |_
 *      | |/ _ \/ __/ __|/ _ \  __/ _` |/ __| __|
 *      | |  __/\__ \__ \  __/ | | (_| | (__| |_
 *      |_|\___||___/___/\___|_|  \__,_|\___|\__|
 *
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author Tessetact Team
 * @link http://www.github.com/TesseractTeam/Tesseract
 *
 *
 */

namespace pocketmine\block;

use pocketmine\item\Item;

class EndPortal extends Transparent{

    protected $id = Block::END_PORTAL;

    public function __construct(){

    }

    public function getName(){
        return "End Portal";
    }

    public function getLightLevel(){
        return 15;
    }

    public function getHardness(){
        return -1;
    }

    public function getResistance(){
        return 18000000;
    }

    public function isBreakable(Item $item){
        return false;
    }
}