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


use pocketmine\math\Vector3;

class AddItemEntityPacket extends DataPacket {

	const NETWORK_ID = ProtocolInfo::ADD_ITEM_ENTITY_PACKET;

	public $eid;
	public $item;
	public $position;
	public $motion;
	public $metadata = [];

	/**
	 *
	 */
	public function decode(){
		$this->eid = $this->getEntityUniqueId();
		$this->entityRuntimeId = $this->getEntityRuntimeId();
		$this->item = $this->getSlot();
		$this->position = $this->getVector3Obj();
		$this->motion = $this->getVector3Obj();
		$this->metadata = $this->getEntityMetadata();
	}

	/**
	 *
	 */
	public function encode(){
		$this->reset();
		if(isset($this->x)) $this->position = new Vector3($this->x, $this->y, $this->z);
		$this->putEntityId($this->eid); //EntityUniqueID
		$this->putEntityId($this->eid); //EntityRuntimeID
		$this->putSlot($this->item);
		$this->putVector3Obj($this->position);
		$this->putVector3ObjNullable($this->motion);
		$this->putEntityMetadata($this->metadata);
	}

	/**
	 * @return AddItemEntityPacket|string
	 */
	public function getName(){
		return "AddItemEntityPacket";
	}

}
