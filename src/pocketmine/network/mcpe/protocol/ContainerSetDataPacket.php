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


class ContainerSetDataPacket extends DataPacket {

	const NETWORK_ID = ProtocolInfo::CONTAINER_SET_DATA_PACKET;

	const PROPERTY_FURNACE_TICK_COUNT = 0;
	const PROPERTY_FURNACE_LIT_TIME = 1;
	const PROPERTY_FURNACE_LIT_DURATION = 2;
	//TODO: check property 3
	const PROPERTY_FURNACE_FUEL_AUX = 4;

	const PROPERTY_BREWING_STAND_BREW_TIME = 0;
	const PROPERTY_BREWING_STAND_FUEL_AMOUNT = 1;
	const PROPERTY_BREWING_STAND_FUEL_TOTAL = 2;

	public $windowid;
	public $property;
	public $value;

	/**
	 *
	 */
	public function decode(){
		$this->windowid = $this->getByte();
		$this->property = $this->getVarInt();
		$this->value = $this->getVarInt();
	}

	/**
	 *
	 */
	public function encode(){
		$this->reset();
		$this->putByte($this->windowid);
		$this->putVarInt($this->property);
		$this->putVarInt($this->value);
	}

	/**
	 * @return string
	 */
	public function getName(){
		return "ContainerSetDataPacket";
	}

}