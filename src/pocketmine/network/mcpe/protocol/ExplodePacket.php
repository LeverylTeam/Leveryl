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

class ExplodePacket extends DataPacket {

	const NETWORK_ID = ProtocolInfo::EXPLODE_PACKET;

	public $position;
	public $radius;
	public $records = [];

	/**
	 * @return $this
	 */
	public function clean(){
		$this->records = [];

		return parent::clean();
	}

	/**
	 *
	 */
	public function decode(){
		$this->position = $this->getVector3Obj();
		$this->radius = (float) ($this->getVarInt() / 32);
		$count = $this->getUnsignedVarInt();
		for($i = 0; $i < $count; ++$i){
			$x = $y = $z = null;
			$this->getSignedBlockPosition($x, $y, $z);
			$this->records[$i] = new Vector3($x, $y, $z);
		}
	}

	/**
	 *
	 */
	public function encode(){
		$this->putVector3Obj($this->position);
		$this->putVarInt((int) ($this->radius * 32));
		$this->putUnsignedVarInt(count($this->records));
		if(count($this->records) > 0){
			foreach($this->records as $record){
				$this->putSignedBlockPosition((int) $record->x, (int) $record->y, (int) $record->z);
			}
		}
	}

	/**
	 * @return string
	 */
	public function getName(){
		return "ExplodePacket";
	}

}