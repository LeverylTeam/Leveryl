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

namespace pocketmine\event\entity;

use pocketmine\entity\Entity;

class EntitySlimeBlockBounceEvent extends EntityEvent{
	public static $handlerList = null;

	/**
	 * @param Entity $entity
	 */
	public function __construct(Entity $entity){
		$this->entity = $entity;
	}

	/**
	 * @return Entity
	 */
	public function getEntity(){
		return $this->entity;
	}
}