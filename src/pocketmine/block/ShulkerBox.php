<?php

/*
 *     __						    _
 *    / /  _____   _____ _ __ _   _| |
 *   / /  / _ \ \ / / _ \ '__| | | | |
 *  / /__|  __/\ V /  __/ |  | |_| | |
 *  \____/\___| \_/ \___|_|   \__, |_|
 *						      |___/
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author LeverylTeam
 * @link https://github.com/LeverylTeam
 *
*/

declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\Tool;

class ShulkerBox extends Transparent {

    protected $id = self::SHULKER_BOX;

    public function __construct($meta = 0){
        $this->meta = $meta;
    }

    public function getToolType(){
        return Tool::TYPE_PICKAXE;
    }

    public function getName(){
        return "Shulker Box";
    }

    public function getHardness(){
        return 6;
    }

    public function getResistance()
    {
        return 30;
    }
}