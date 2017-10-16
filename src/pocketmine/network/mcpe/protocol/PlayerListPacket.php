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

	// 2D Array
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
			$entry = [];

			if($this->type === self::TYPE_ADD){
				$entry[0] = $this->getUUID(); // UUID
				$entry[1] = $this->getEntityUniqueId(); // eID
				$entry[2] = $this->getString(); // Username
				$entry[3] = $this->getString(); // SkinID
				$entry[4] = $this->getString(); // SkinData
				
				// Misc
				$entry[5] = $this->getString(); // CapeData
				$entry[6] = $this->getString(); // GeometryModel
				$entry[7] = $this->getString(); // GeometryData
				$entry[8] = $this->getString(); // XBoxUserID
			}else{
				$entry[0] = $this->getUUID();
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
				$this->putUUID($entry[0]);
				$this->putEntityUniqueId($entry[1]);
				$this->putString($entry[2]);
				$this->putString($entry[3]);
				$this->putString($entry[4]);
				
				// Misc
				$this->putString($entry[5] ?? "");
				$this->putString($entry[6] ?? "");
				$this->putString($entry[7] ?? "");
				$this->putString($entry[8] ?? "");
			}else{
				$this->putUUID($entry[0]);
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
