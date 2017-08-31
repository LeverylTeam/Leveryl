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

namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\level\Position;
use pocketmine\level\sound\GenericSound;
use pocketmine\Player;

class DragonEgg extends Fallable {
	protected $id = self::DRAGON_EGG;

	/**
	 * DragonEgg constructor.
	 *
	 * @param int $meta
	 */
	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	/**
	 * @return string
	 */
	public function getName(){
		return "Dragon Egg";
	}

	/**
	 * @return int
	 */
	public function getHardness(){
		return 4.5;
	}

	/**
	 * @return int
	 */
	public function getResistance(){
		return 45;
	}

	/**
	 * @return int
	 */
	public function getLightLevel(){
		return 1;
	}

	/**
	 * @param Item $item
	 *
	 * @return bool
	 */
	public function isBreakable(Item $item){
		return false;
	}

	public function canBeActivated(): bool{
		return true;
	}

	public function onActivate(Item $item, Player $player = null){
		$RAND_VERTICAL = [-7,-6,-5,-4,-3,-2,-1,0,1,2,3,4,5,6,7];
		$RAND_HORIZONTAL = [-15,-14,-13,-12,-11,-10,-9,-8,-7,-6,-5,-4,-3,-2,-1,0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15];
		$safe = false;
		while(!$safe){
			$level = $this->getLevel();
			$x = $this->getX() + $RAND_HORIZONTAL[array_rand($RAND_HORIZONTAL)];
			$y = $this->getY() + $RAND_VERTICAL[array_rand($RAND_VERTICAL)];
			$z = $this->getZ() + $RAND_HORIZONTAL[array_rand($RAND_HORIZONTAL)];
			if($level->getBlockIdAt($x,$y,$z) == 0 && $level->getBlockIdAt($x,$y - 1,$z) != 0){
				$level->setBlock($this, new Air(), false, false);
				$oldpos = clone $this;
				$pos = new Position($x, $y, $z, $level);
				$newpos = $pos;
				$level->setBlock($pos, $this);

				$posdistance = new Position($newpos->x - $oldpos->x, $newpos->y - $oldpos->y, $newpos->z - $oldpos->z, $this->getLevel());
				$intdist = $oldpos->distance($newpos);
				for($c = 0; $c <= $intdist; $c++){

					$progress = $c / $intdist;

					$this->getLevel()->addSound(new GenericSound(new Position($oldpos->x + $posdistance->x * $progress, 1.62 + $oldpos->y + $posdistance->y * $progress, $oldpos->z + $posdistance->z * $progress, $this->getLevel()), 2010));
				}
				$safe = true;
				break;
			}
		}
		return $safe; // Unnecessary but added just to stop PHPStorm from whining... And, Why not.
	}
}
