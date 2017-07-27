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

namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\math\AxisAlignedBB;
use pocketmine\Player;

class EndPortalFrame extends Solid
{

	protected $id = self::END_PORTAL_FRAME;

	public function __construct($meta = 0)
	{
		$this->meta = $meta;
	}

	public function getLightLevel()
	{
		return 1;
	}

	public function getName()
	{
		return "End Portal Frame";
	}

	public function getHardness()
	{
		return -1;
	}

	public function getResistance()
	{
		return 18000000;
	}

	public function isBreakable(Item $item)
	{
		return false;
	}

	protected function recalculateBoundingBox()
	{

		return new AxisAlignedBB(
			$this->x,
			$this->y,
			$this->z,
			$this->x + 1,
			$this->y + 2, //(($this->getDamage() & 0x04) > 0 ? 1 : 0.8125)
			$this->z + 1
		);
	}

	// FIXME: This is still un-verified.
	// FIXME: This night NOT be how the direction -> meta conversion works in vanilla.
	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null)
	{
		switch($player->getDirection()){
			case 0:
				$this->meta = 0;
				break;
			case 1:
				$this->meta = 1;
				break;
			case 2:
				$this->meta = 3;
				break;
			case 3:
				$this->meta = 2;
				break;
			default:
				$this->meta = 0;
				break;
		}
	}
}