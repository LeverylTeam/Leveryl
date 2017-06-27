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
use pocketmine\level\Level;

class ConcretePowder extends Fallable{

	protected $id = self::CONCRETE_POWDER;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getToolType(){
		return Tool::TYPE_SHOVEL;
	}

	public function getName(){
		return "Concrete Powder";
	}

	public function getHardness(){
		return 0.5;
	}

    public function onUpdate($type)
    {
        if($type === Level::BLOCK_UPDATE_NORMAL){
            for($s = 0; $s <= 6; ++$s){
                $side = $this->getSide($s);
                if($side instanceof Water){
                    $this->getLevel()->setBlock($this, new Concrete(), true);
                }
            }
            return Level::BLOCK_UPDATE_NORMAL;
        }elseif($type === Level::BLOCK_UPDATE_RANDOM){
            for($s = 0; $s <= 6; ++$s){
                $side = $this->getSide($s);
                if($side instanceof Water){
                    $this->getLevel()->setBlock($this, new Concrete(), true);
                }
            }
        }

        return false;
    }
}