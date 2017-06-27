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

use pocketmine\item\Item;
use pocketmine\item\Tool;

class Concrete extends Solid{

	protected $id = self::CONCRETE;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getToolType(){
		return Tool::TYPE_PICKAXE;
	}

    public function getName(){
        static $names = [
            0 => "White Concrete",
            1 => "Orange Concrete",
            2 => "Magenta Concrete",
            3 => "Light Blue Concrete",
            4 => "Yellow Concrete",
            5 => "Lime Concrete",
            6 => "Pink Concrete",
            7 => "Gray Concrete",
            8 => "Silver Concrete",
            9 => "Cyan Concrete",
            10 => "Purple Concrete",
            11 => "Blue Concrete",
            12 => "Brown Concrete",
            13 => "Green Concrete",
            14 => "Red Concrete",
            15 => "Black Concrete",
        ];
        return $names[$this->meta & 0x0f];
    }

    public function getHardness(){
        return 1.8;
    }

    public function getResistance()
    {
        return 9;
    }
}