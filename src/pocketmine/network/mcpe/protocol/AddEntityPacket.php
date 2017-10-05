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

#ifndef COMPILE

use pocketmine\entity\Attribute;
use pocketmine\math\Vector3;

#endif

class AddEntityPacket extends DataPacket {

	const NETWORK_ID = ProtocolInfo::ADD_ENTITY_PACKET;

	public $eid;
	public $type;
	public $position;
	public $motion;
	public $yaw;
	public $pitch;
	/** @var Attribute[] */
	public $attributes = [];
	public $metadata = [];
	public $links = [];

	/**
	 *
	 */
	public function decode(){
		$this->eid = $this->getEntityUniqueId();
		$this->entityRuntimeId = $this->getEntityRuntimeId();
		$this->type = $this->getUnsignedVarInt();
		$this->position = $this->getVector3Obj();
		$this->motion = $this->getVector3Obj();
		$this->pitch = $this->getLFloat();
		$this->yaw = $this->getLFloat();

		$attrCount = $this->getUnsignedVarInt();
		for($i = 0; $i < $attrCount; ++$i){
			$name = $this->getString();
			$min = $this->getLFloat();
			$current = $this->getLFloat();
			$max = $this->getLFloat();
			$attr = Attribute::getAttributeByName($name);

			if($attr !== null){
				$attr->setMinValue($min);
				$attr->setMaxValue($max);
				$attr->setValue($current);
				$this->attributes[] = $attr;
			}else{
				throw new \UnexpectedValueException("Unknown attribute type \"$name\"");
			}
		}

		$this->metadata = $this->getEntityMetadata();
		$linkCount = $this->getUnsignedVarInt();
		for($i = 0; $i < $linkCount; ++$i){
			$this->links[] = $this->getEntityLink();
		}
	}

	/**
	 *
	 */
	public function encode(){
		$this->reset();
		if(isset($this->x)) $this->position = new Vector3($this->x, $this->y, $this->z);
		$this->putEntityId($this->eid); //EntityUniqueID - TODO: verify this
		$this->putEntityId($this->eid);
		$this->putUnsignedVarInt($this->type);
		$this->putVector3Obj($this->position);
		$this->putVector3ObjNullable($this->motion);
		$this->putLFloat($this->pitch * (256 / 360));
		$this->putLFloat($this->yaw * (256 / 360));
		$this->putUnsignedVarInt(count($this->attributes));
		foreach($this->attributes as $entry){
			$this->putString($entry->getName());
			$this->putLFloat($entry->getMinValue());
			$this->putLFloat($entry->getValue());
			$this->putLFloat($entry->getMaxValue());
		}
		$this->putEntityMetadata($this->metadata);
		$this->putUnsignedVarInt(count($this->links));
		foreach($this->links as $link){
			$this->putEntityLink($link);
		}
	}

	/**
	 * @return AddEntityPacket|string
	 */
	public function getName(){
		return "AddEntityPacket";
	}

}
