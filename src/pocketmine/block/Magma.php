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
 * Originally by @PMMP
 * Modified by @LeverylTeam to work Vanilla-Like
 *
*/

namespace pocketmine\block;

use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityDamageByBlockEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\Item;
use pocketmine\item\Tool;

class Magma extends Solid{

	protected $id = Block::MAGMA;

	/**
	 * Magma constructor.
	 *
	 * @param int $meta
	 */
	public function __construct(int $meta = 0){
		$this->meta = $meta;
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		return "Magma Block";
	}

	/**
	 * @return float
	 */
	public function getHardness() : float{
		return 0.5;
	}

	/**
	 * @return int
	 */
	public function getToolType() : int{
		return Tool::TYPE_PICKAXE;
	}

	/**
	 * @return int
	 */
	public function getLightLevel() : int{
		return 3;
	}

	/**
	 * @return bool
	 */
	public function hasEntityCollision() : bool{
		return true;
	}

	/**
	 * @param Entity $entity
	 */
	public function onEntityCollide(Entity $entity){
		$ev = new EntityDamageByBlockEvent($this, $entity, EntityDamageEvent::CAUSE_FIRE, 1);
		if($entity->attack($ev->getFinalDamage(), $ev) === true){
			$ev->useArmors();
		}
	}

	/**
	 * @param Item $item
	 *
	 * @return array
	 */
	public function getDrops(Item $item) : array{
		if($item->isPickaxe() >= Tool::TIER_WOODEN){
			return [
				[$this->id, 0, 1],
			];
		}

		return [];
	}

}