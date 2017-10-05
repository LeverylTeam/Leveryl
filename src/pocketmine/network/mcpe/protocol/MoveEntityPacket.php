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

class MoveEntityPacket extends DataPacket {

	const NETWORK_ID = ProtocolInfo::MOVE_ENTITY_PACKET;

	public $eid;
	public $position;
	public $yaw;
	public $headYaw;
	public $pitch;
	public $byte1;

	/**
	 *
	 */
	public function decode(){
		$this->eid = $this->getEntityId();
		$this->position = $this->getVector3Obj();
		$this->pitch = $this->getByte() * (360.0 / 256);
		$this->yaw = $this->getByte() * (360.0 / 256);
		$this->headYaw = $this->getByte() * (360.0 / 256);
		$this->byte1 = $this->getByte();
	}

	/**
	 *
	 */
	public function encode(){
		$this->reset();
		if(isset($this->x)) $this->position = new Vector3($this->x, $this->y, $this->z);
		$this->putEntityId($this->eid);
		$this->putVector3Obj($this->position);
		$this->putByte($this->pitch / (360.0 / 256));
		$this->putByte($this->yaw / (360.0 / 256));
		$this->putByte($this->headYaw / (360.0 / 256));
		$this->putByte($this->byte1);
	}

}
