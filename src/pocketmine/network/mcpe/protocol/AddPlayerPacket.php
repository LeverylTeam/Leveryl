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

use pocketmine\math\Vector3;

class AddPlayerPacket extends DataPacket {

	const NETWORK_ID = ProtocolInfo::ADD_PLAYER_PACKET;

	public $uuid;
	public $username;
	public $eid;
	public $position;
	public $motion;
	public $pitch = 0.0;
	public $headYaw = null; //TODO
	public $yaw = 0.0;
	public $item;
	public $metadata = [];

	//TODO: adventure settings stuff
	public $uvarint1 = 0;
	public $uvarint2 = 0;
	public $uvarint3 = 0;
	public $uvarint4 = 0;
	public $uvarint5 = 0;

	public $long1 = 0;

	public $links = [];

	/**
	 *
	 */
	public function decode(){
		$this->uuid = $this->getUUID();
		$this->username = $this->getString();
		$this->eid = $this->getEntityUniqueId();
		$this->entityRuntimeId = $this->getEntityRuntimeId();
		$this->position = $this->getVector3Obj();
		$this->motion = $this->getVector3Obj();
		$this->pitch = $this->getLFloat();
		$this->headYaw = $this->getLFloat();
		$this->yaw = $this->getLFloat();
		$this->item = $this->getSlot();
		$this->metadata = $this->getEntityMetadata();

		$this->uvarint1 = $this->getUnsignedVarInt();
		$this->uvarint2 = $this->getUnsignedVarInt();
		$this->uvarint3 = $this->getUnsignedVarInt();
		$this->uvarint4 = $this->getUnsignedVarInt();
		$this->uvarint5 = $this->getUnsignedVarInt();

		$this->long1 = $this->getLLong();

		$linkCount = $this->getUnsignedVarInt();
		for($i = 0; $i < $linkCount; ++$i){
			$this->links[$i] = $this->getEntityLink();
		}
	}

	/**
	 *
	 */
	public function encode(){
		$this->reset();
		if(isset($this->x)) $this->position = new Vector3($this->x, $this->y, $this->z);
		$this->putUUID($this->uuid);
		$this->putString($this->username);
		$this->putEntityId($this->eid); //EntityUniqueID
		$this->putEntityId($this->eid); //EntityRuntimeID
		$this->putVector3Obj($this->position);
		$this->putVector3ObjNullable($this->motion);
		$this->putLFloat($this->pitch);
		$this->putLFloat($this->headYaw ?? $this->yaw);
		$this->putLFloat($this->yaw);
		$this->putSlot($this->item);
		$this->putEntityMetadata($this->metadata);

		$this->putUnsignedVarInt($this->uvarint1);
		$this->putUnsignedVarInt($this->uvarint2);
		$this->putUnsignedVarInt($this->uvarint3);
		$this->putUnsignedVarInt($this->uvarint4);
		$this->putUnsignedVarInt($this->uvarint5);

		$this->putLLong($this->long1);

		$this->putUnsignedVarInt(count($this->links));
		foreach($this->links as $link){
			$this->putEntityLink($link);
		}
	}

	/**
	 * @return string
	 */
	public function getName(){
		return "AddPlayerPacket";
	}

}
