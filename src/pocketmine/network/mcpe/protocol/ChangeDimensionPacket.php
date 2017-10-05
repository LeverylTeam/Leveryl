<?php

/*
 *
 *  _____            _               _____           
 * / ____|          (_)             |  __ \          
 *| |  __  ___ _ __  _ ___ _   _ ___| |__) | __ ___  
 *| | |_ |/ _ \ '_ \| / __| | | / __|  ___/ '__/ _ \ 
 *| |__| |  __/ | | | \__ \ |_| \__ \ |   | | | (_) |
 * \_____|\___|_| |_|_|___/\__, |___/_|   |_|  \___/ 
 *                         __/ |                    
 *                        |___/                     
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author GenisysPro
 * @link https://github.com/GenisysPro/GenisysPro
 *
 *
*/

namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>

class ChangeDimensionPacket extends DataPacket {

	const NETWORK_ID = ProtocolInfo::CHANGE_DIMENSION_PACKET;

	const DIMENSION_NORMAL = 0;
	const DIMENSION_NETHER = 1;
	const DIMENSION_END = 2;

	public $dimension;
	public $respawn = false;

	public $position;

	public function decode(){
		$this->dimension = $this->getVarInt();
		$this->position = $this->getVector3Obj();
		$this->respawn = $this->getBool();
	}

	public function encode(){
		$this->reset();
		$this->putVarInt($this->dimension);
		$this->putVector3Obj($this->position);
		$this->putBool($this->respawn);
	}

	public function getName(){
		return "ChangeDimensionPacket";
	}

}