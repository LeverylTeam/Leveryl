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

declare(strict_types=1);

namespace pocketmine\event\server;

use pocketmine\network\SourceInterface;

/**
 * Called when a network interface crashes, with relevant crash information.
 */
class NetworkInterfaceCrashEvent extends NetworkInterfaceEvent{
	public static $handlerList = null;

	/**
	 * @var \Throwable
	 */
	private $exception;

	public function __construct(SourceInterface $interface, \Throwable $throwable){
		parent::__construct($interface);
		$this->exception = $throwable;
	}

	/**
	 * @return \Throwable
	 */
	public function getCrashInformation() : \Throwable{
		return $this->exception;
	}
}