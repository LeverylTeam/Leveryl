<?php

/*
 *
 *  _____   _____   __   _   _   _____  __    __  _____
 * /  ___| | ____| |  \ | | | | /  ___/ \ \  / / /  ___/
 * | |     | |__   |   \| | | | | |___   \ \/ /  | |___
 * | |  _  |  __|  | |\   | | | \___  \   \  /   \___  \
 * | |_| | | |___  | | \  | | |  ___| |   / /     ___| |
 * \_____/ |_____| |_|  \_| |_| /_____/  /_/     /_____/
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author iTX Technologies
 * @link https://itxtech.org
 *
 */

namespace pocketmine\block;

use pocketmine\item\Item;


class PoweredRepeater extends Solid {
	protected $id = self::POWERED_REPEATER_BLOCK;

	const ACTION_ACTIVATE = "Repeater Activate";
	const ACTION_DEACTIVATE = "Repeater Deactivate";

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getName() : string{
		return "Powered Repeater";
	}

	public function canBeActivated() : bool{
		return true;
	}

	public function getDrops(Item $item) : array{
		return [
			[Item::REPEATER, 0, 1]
		];
	}
}
