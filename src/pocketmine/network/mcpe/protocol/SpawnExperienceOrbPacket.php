<?php

namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>


class SpawnExperienceOrbPacket extends DataPacket {

	const NETWORK_ID = ProtocolInfo::SPAWN_EXPERIENCE_ORB_PACKET;

	public $position;
	public $amount;

	/**
	 *
	 */
	public function decode(){
		$this->position = $this->getVector3Obj();
		$this->amount = $this->getVarInt();
	}

	/**
	 *
	 */
	public function encode(){
		$this->reset();
		$this->putVector3Obj($this->position);
		$this->putVarInt($this->amount);
	}

	/**
	 * @return string
	 */
	public function getName(){
		return "SpawnExperienceOrbPacket";
	}

}
