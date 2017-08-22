<?php

/**
 *
 *  ____       _                          _
 * |  _ \ _ __(_)___ _ __ ___   __ _ _ __(_)_ __   ___
 * | |_) | '__| / __| '_ ` _ \ / _` | '__| | '_ \ / _ \
 * |  __/| |  | \__ \ | | | | | (_| | |  | | | | |  __/
 * |_|   |_|  |_|___/_| |_| |_|\__,_|_|  |_|_| |_|\___|
 *
 * Prismarine is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author Prismarine Team
 * @link   https://github.com/PrismarineMC/Prismarine
 *
 *
 */

namespace pocketmine\inventory;

use pocketmine\block\Block;
use pocketmine\math\Vector3;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\network\mcpe\protocol\BlockEntityDataPacket;
use pocketmine\network\mcpe\protocol\UpdateBlockPacket;
use pocketmine\Player;
use pocketmine\tile\Tile;

class WindowInventory extends CustomInventory {

	protected $customName = "";
	protected $tile;
	protected $block;
	protected $oldID;

	public function __construct(Player $player, $size = 27, $name = ""){
		$this->tile = Tile::CHEST;
		$this->block = 54;
		$type = InventoryType::get(InventoryType::CHEST);
		switch($size){
			case 5:
				$this->tile = Tile::HOPPER;
				$this->block = 154;
				$type = InventoryType::get(InventoryType::HOPPER);
				break;
			case 9:
				$this->tile = Block::WORKBENCH;
				$this->block = 58;
				$type = InventoryType::get(InventoryType::WORKBENCH);
				break;
			case 27:
				$type = InventoryType::get(InventoryType::CHEST);
				$this->tile = Tile::CHEST;
				$this->block = 54;
				break;
			case 2:
				$type = InventoryType::get(InventoryType::ENCHANT_TABLE);
				$this->tile = Tile::ENCHANT_TABLE;
				$this->block = 116;
				break;
			case 3:
				$type = InventoryType::get(InventoryType::FURNACE);
				$this->tile = Tile::FURNACE;
				$this->block = 61;
				break;
			case 1:
				$type = InventoryType::get(InventoryType::BREWING_STAND);
				$this->tile = Tile::BREWING_STAND;
				$this->block = 117;
				break;
			default:
				$player->getServer()->getLogger()->notice("Unknown window size. If must be one from: 1, 2, 3, 5, 9, 27, 54. Using default size(27).");
		}
		$this->customName = $name;
		$holder = new WindowHolder($player->getFloorX(), $player->getFloorY(), $player->getFloorZ(), $this);
		parent::__construct($holder, $type);
	}

	public function onOpen(Player $who){
		$this->holder = $holder = new WindowHolder($who->getFloorX(), $who->getFloorY(), $who->getFloorZ(), $this);
		$this->oldID[$who->getName()] = $who->getLevel()->getBlockIdAt($holder->x, $holder->y, $holder->z);
		$pk = new UpdateBlockPacket();
		$pk->x = $holder->x;
		$pk->y = $holder->y;
		$pk->z = $holder->z;
		$pk->blockId = $this->block;
		$pk->blockData = 0;
		$pk->flags = UpdateBlockPacket::FLAG_ALL;
		$who->dataPacket($pk);
		$c = new CompoundTag("", [
			new StringTag("id", $this->tile),
			new IntTag("x", (int)$holder->x),
			new IntTag("y", (int)$holder->y),
			new IntTag("z", (int)$holder->z),
		]);
		if($this->name !== ""){
			$c->CustomName = new StringTag("CustomName", $this->customName);
		}
		$nbt = new NBT(NBT::LITTLE_ENDIAN);
		$nbt->setData($c);
		$pk = new BlockEntityDataPacket();
		$pk->x = $holder->x;
		$pk->y = $holder->y;
		$pk->z = $holder->z;
		$pk->namedtag = $nbt->write();
		$who->dataPacket($pk);
		parent::onOpen($who);
		$this->sendContents($who);
	}

	public function onClose(Player $who){
		$holder = $this->holder;
		$pk = new UpdateBlockPacket();
		$pk->x = $holder->x;
		$pk->y = $holder->y;
		$pk->z = $holder->z;
		$pk->blockId = $who->getLevel()->getBlockIdAt($holder->x, $holder->y, $holder->z);
		$pk->blockData = $who->getLevel()->getBlockDataAt($holder->x, $holder->y, $holder->z);
		$pk->flags = UpdateBlockPacket::FLAG_ALL;
		$who->dataPacket($pk);
		$who->getLevel()->setBlock(new Vector3($holder->x, $holder->y, $holder->z), $this->oldID[$who->getName()]);
		$this->oldID[$who->getName()] = 0;
		parent::onClose($who);
	}
}
