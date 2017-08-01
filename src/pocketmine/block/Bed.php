<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

/* Implementation Bed Color it's by Leveryl and PMMP, not other software.
Please don't copy code, it's my implementation AND NOT GIVE PERMISSION TO OTHERS 
Credits: @NycuRO on 1.08.2017 */

declare(strict_types = 1);

namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\level\Explosion;
use pocketmine\level\Level;
use pocketmine\math\AxisAlignedBB;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\Player;
use pocketmine\tile\Bed as TileBed;
use pocketmine\tile\Tile;
use pocketmine\utils\TextFormat;

class Bed extends Transparent
{
	const BITFLAG_OCCUPIED = 0x04;
	const BITFLAG_HEAD = 0x08;

	/**
	 * @var int
	 */
	protected $id = self::BED_BLOCK;
	
	const WHITE_BED = 0;
	const LIGHT_GRAY_BED = 1;
	const GRAY_BED = 2;
	const BLACK_BED = 3;
	const BROWN_BED = 4;
	const RED_BED = 5;
	const ORANGE_BED = 6;
	const YELLOW_BED = 7;
	const LIME_BED = 8;
	const GREEN_BED = 9;
	const CYAN_BED = 10;
	const LIGHT_BLUE_BED = 11;
	const BLUE_BED = 12;
	const PURPLE_BED = 13;
	const MAGENTA_BED = 14;
	const PINK_BED = 15;

	/**
	 * Bed constructor.
	 * @param int $meta
	 */
	public function __construct($meta = 0)
	{
		$this->meta = $meta;
	}

	/**
	 * @return bool
	 */
	public function canBeActivated(): bool
	{
		return true;
	}

	/**
	 * @return float
	 */
	public function getHardness()
	{
		return 0.2;
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		static $names = [
			0 => "White Bed",
			1 => "Light Grey Bed",
			2 => "Grey Bed",
			3 => "Black Bed",
			4 => "Brown Bed",
			5 => "Red Bed",
			6 => "Orange Bed",
			7 => "Yellow Bed",
			8 => "Lime Bed",
			9 => "Green Bed",
			10 => "Cyan Bed",
			11 => "Light Blue Bed",
			12 => "Blue Bed",
			13 => "Purple Bed",
			14 => "Magenta Bed",
			15 => "Pink Bed",
		];
		return $names[$this->meta & 0x0f];
	}

	/**
	 * @return AxisAlignedBB
	 */
	protected function recalculateBoundingBox()
	{
		return new AxisAlignedBB(
			$this->x,
			$this->y,
			$this->z,
			$this->x + 1,
			$this->y + 0.5625,
			$this->z + 1
		);
	}

	/**
	 * @param Item $item
	 * @param Player|null $player
	 * @return bool
	 */
	public function onActivate(Item $item, Player $player = null)
	{
		if ($this->getLevel()->getDimension() == Level::DIMENSION_NETHER)
		{
			$explosion = new Explosion($this, 6, $this);
			$explosion->explodeA();
			return true;
		}
		$time = $this->getLevel()->getTime() % Level::TIME_FULL;
		$isNight = ($time >= Level::TIME_NIGHT and $time < Level::TIME_SUNRISE);
		if ($player instanceof Player and !$isNight)
		{
			$player->sendMessage(TextFormat::GRAY . "You can only sleep at night"); //TODO; Translate it
			return true;
		}
		$blockNorth = $this->getSide(2); //Gets the blocks around them
		$blockSouth = $this->getSide(3);
		$blockEast = $this->getSide(5);
		$blockWest = $this->getSide(4);
		if (($this->meta & 0x08) === 0x08)
		{ //This is the Top part of bed
			$b = $this;
		}
		else
		{ //Bottom Part of Bed
			if ($blockNorth->getId() === $this->id and ($blockNorth->meta & 0x08) === 0x08)
			{
				$b = $blockNorth;
			}
			elseif ($blockSouth->getId() === $this->id and ($blockSouth->meta & 0x08) === 0x08)
			{
				$b = $blockSouth;
			}
			elseif ($blockEast->getId() === $this->id and ($blockEast->meta & 0x08) === 0x08)
			{
				$b = $blockEast;
			}
			elseif ($blockWest->getId() === $this->id and ($blockWest->meta & 0x08) === 0x08)
			{
				$b = $blockWest;
			}
			else
			{
				if ($player instanceof Player)
				{
					$player->sendMessage(TextFormat::GRAY . "This bed is incomplete"); //TODO; Translate it
				}
				return true;
			}
		}
		if ($player instanceof Player and $player->sleepOn($b) === false)
		{
			$player->sendMessage(TextFormat::GRAY . "This bed is occupied"); //TODO; Translate it
		}
		return true;
	}

	/**
	 * @param Item $item
	 * @param Block $block
	 * @param Block $target
	 * @param int $face
	 * @param float $fx
	 * @param float $fy
	 * @param float $fz
	 * @param Player|null $player
	 * @return bool
	 */
	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null)
	{
		$down = $this->getSide(0);
		if ($down->isTransparent() === false)
		{
			$faces = [
				0 => 3,
				1 => 4,
				2 => 2,
				3 => 5,
			];
			$d = $player instanceof Player ? $player->getDirection() : 0;
			$next = $this->getSide($faces[($d + 3) % 4]);
			$downNext = $this->getSide(0);
			if ($next->canBeReplaced() === true and $downNext->isTransparent() === false)
			{
				$meta = (($d + 3) % 4) & 0x03;
				$this->getLevel()->setBlock($block, Block::get($this->id, $meta), true, true);
				$this->getLevel()->setBlock($next, Block::get($this->id, $meta | 0x08), true, true);
				$nbt = new CompoundTag("", [
					new StringTag("id", Tile::BED),
					new ByteTag("color", $item->getDamage() & 0x0f),
					new IntTag("x", $block->x),
					new IntTag("y", $block->y),
					new IntTag("z", $block->z),
				]);
				$nbt2 = clone $nbt;
				$nbt2["x"] = $next->x;
				$nbt2["z"] = $next->z;
				Tile::createTile(Tile::BED, $this->getLevel(), $nbt);
				Tile::createTile(Tile::BED, $this->getLevel(), $nbt2);
				return true;
			}
		}
		return false;
	}

	/**
	 * @param Item $item
	 * @return bool
	 */
	public function onBreak(Item $item)
	{
		$sides = [
			0  => 3,
			1  => 4,
			2  => 2,
			3  => 5,
			8  => 2,
			9  => 5,
			10 => 3,
			11 => 4,
		];
		if (($this->meta & 0x08) === 0x08)
		{ //This is the Top part of bed
			$next = $this->getSide($sides[$this->meta]);
			if ($next->getId() === $this->id and ($next->meta | 0x08) === $this->meta)
			{ //Checks if the block ID and meta are right
				$this->getLevel()->setBlock($next, new Air(), true, true);
			}
		}
		else
		{ //Bottom Part of Bed
			$next = $this->getSide($sides[$this->meta]);
			if ($next->getId() === $this->id and $next->meta === ($this->meta | 0x08))
			{
				$this->getLevel()->setBlock($next, new Air(), true, true);
			}
		}
		$this->getLevel()->setBlock($this, new Air(), true, true);
		return true;
	}

	/**
	 * @param Item $item
	 * @return array
	 */
	public function getDrops(Item $item) : array
	{
		if ($this->isHeadPart())
		{
			$tile = $this->getLevel()->getTile($this);
			if ($tile instanceof TileBed)
			{
				return [
					[Item::BED, $tile->getColor(), 1]
				];
			}
			else
			{
				return [
					[Item::BED, 14, 1] //Red
				];
			}
		}
		else
		{
			return [];
		}
	}

	public function getVariantBitmask()
	{
		return 0x08;
	}
}
