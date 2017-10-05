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

namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>


use pocketmine\network\mcpe\protocol\types\PlayerListEntry;

class PlayerListPacket extends DataPacket {

	const NETWORK_ID = ProtocolInfo::PLAYER_LIST_PACKET;

	const TYPE_ADD = 0;
	const TYPE_REMOVE = 1;

	//REMOVE: UUID, ADD: UUID, entity id, name, skinId, skin
	/** @var PlayerListEntry[] */
	public $entries = [];
	public $type;

	/**
	 * @return $this
	 */
	public function clean(){
		$this->entries = [];

		return parent::clean();
	}

	/**
	 *
	 */
	public function decode(){
		$this->type = $this->getByte();
		$count = $this->getUnsignedVarInt();
		for($i = 0; $i < $count; ++$i){
			$entry = new PlayerListEntry();

			if($this->type === self::TYPE_ADD){
				$entry->uuid = $this->getUUID();
				$entry->entityUniqueId = $this->getEntityUniqueId();
				$entry->username = $this->getString();
				$entry->skinId = $this->getString();
				$entry->skinData = $this->getString();
				$entry->capeData = $this->getString();
				$entry->geometryModel = $this->getString();
				$entry->geometryData = $this->getString();
				$entry->xboxUserId = $this->getString();
			}else{
				$entry->uuid = $this->getUUID();
			}

			$this->entries[$i] = $entry;
		}
	}

	/**
	 *
	 */
	public function encode(){
		$this->reset();
		$this->putByte($this->type);
		$this->putUnsignedVarInt(count($this->entries));
		foreach($this->entries as $entry){
			if($this->type === self::TYPE_ADD){
				$this->putUUID($entry->uuid);
				$this->putEntityUniqueId($entry->entityUniqueId);
				$this->putString($entry->username);
				$this->putString($entry->skinId);
				$this->putString($entry->skinData);
				$this->putString($entry->capeData);
				$this->putString($entry->geometryModel);
				$this->putString($entry->geometryData);
				$this->putString($entry->xboxUserId);
			}else{
				$this->putUUID($entry->uuid);
			}
		}
	}

	/**
	 * @return string
	 */
	public function getName(){
		return "PlayerListPacket";
	}

}
