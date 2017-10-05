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


class BlockPickRequestPacket extends DataPacket {

	const NETWORK_ID = ProtocolInfo::BLOCK_PICK_REQUEST_PACKET;

	public $x;
	public $y;
	public $z;
	public $addUserData = false;
	public $hotbarSlot;

	/**
	 *
	 */
	public function decode(){
		$this->getSignedBlockPosition($this->tileX, $this->tileY, $this->tileZ);
		$this->addUserData = $this->getBool();
		$this->hotbarSlot = $this->getByte();
	}

	/**
	 *
	 */
	public function encode(){
		$this->putSignedBlockPosition($this->tileX, $this->tileY, $this->tileZ);
		$this->putBool($this->addUserData);
		$this->putByte($this->hotbarSlot);
	}

	/**
	 * @return string
	 */
	public function getName(){
		return "BlockPickRequestPacket";
	}

}
