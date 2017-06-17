<?php

/*
 *
 *  _____   _____   __   _   _   _____  __    __  _____
 * /  ___| | ____| |  \ | | | | /  ___/ \ \  / / /  ___/
 * | |     | |__   |   \| | | | | |___   \ \/ /  | |___
 * | |  _  |  __|  | |\   | | | \___  \   \  /   \___  \
 * | |_| | | |___  | | \  | | |  ___| |   / /     ___| |
 * \_____/ |_____| |_|  \_| |_| /_____/  /_/     /_____/
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author iTX Technologies
 * @link https://itxtech.org
 *
 */

namespace pocketmine\block;


use pocketmine\item\Item;

use pocketmine\math\Vector3;


use pocketmine\Player;

class PressurePlate extends Solid{

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function hasEntityCollision(){
		return true;
	}

	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
		$below = $this->getSide(Vector3::SIDE_DOWN);
		if($below instanceof Transparent) return;
		else $this->getLevel()->setBlock($block, $this, true, false);
	}

	public function onBreak(Item $item){
		$this->getLevel()->setBlock($this, new Air(), true);
	}

	public function getHardness() {
		return 0.5;
	}

	public function getResistance(){
		return 2.5;
	}
}
