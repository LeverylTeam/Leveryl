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

declare(strict_types = 1);

namespace pocketmine\event\block;

use pocketmine\block\Block;
use pocketmine\event\Cancellable;
use pocketmine\level\Position;

class BlockTeleportEvent extends BlockEvent implements Cancellable {
	public static $handlerList = null;

	/** @var Position */
	protected $oldPosition, $newPosition;

	public function __construct(Block $block, Position $oldPosition, Position $newPosition){
		$this->block = $block;
		$this->oldPosition = $oldPosition;
		$this->newPosition = $newPosition;
	}

	public function getBlock() : Block {
		return $this->block;
	}

	public function getOldPosition() : Position {
		return $this->oldPosition;
	}

	public function getNewPosition() : Position {
		return $this->newPosition;
	}
}