<?php

/*
 *
 *    _______                                _
 *   |__   __|                              | |
 *      | | ___  ___ ___  ___ _ __ __ _  ___| |_
 *      | |/ _ \/ __/ __|/ _ \  __/ _` |/ __| __|
 *      | |  __/\__ \__ \  __/ | | (_| | (__| |_
 *      |_|\___||___/___/\___|_|  \__,_|\___|\__|
 *
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author Tesseract Team
 * @link http://www.github.com/TesseractTeam/Tesseract
 * 
 *
 */

namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>

class PlaySoundPacket extends DataPacket {

	const NETWORK_ID = ProtocolInfo::PLAY_SOUND_PACKET;

	public $sound;
	public $x;
	public $y;
	public $z;
	public $volume;
	public $float;

	/**
	 *
	 */
	public function decode(){
		$this->sound = $this->getString();
		$this->getBlockPosition($this->x, $this->y, $this->z);
		$this->x /= 8;
		$this->y /= 8;
		$this->z /= 8;
		$this->volume = $this->getFloat();
		$this->float = $this->getFloat();
	}

	/**
	 *
	 */
	public function encode(){
		$this->reset();
		$this->putString($this->sound);
		$this->putBlockPosition((int) ($this->x * 8), (int) ($this->y * 8), (int) ($this->z * 8)); // This needs to be typecasted because XYZ coords can be float values.
		$this->putFloat($this->volume);
		$this->putFloat($this->float);
	}

	/**
	 * @return string
	 */
	public function getName(){
		return "PlaySoundPacket";
	}

}
