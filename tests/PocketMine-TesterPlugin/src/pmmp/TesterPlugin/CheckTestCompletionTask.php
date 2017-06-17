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

namespace pmmp\TesterPlugin;

use pocketmine\scheduler\PluginTask;

class CheckTestCompletionTask extends PluginTask{

	public function onRun($currentTick){
		/** @var Main $owner */
		$owner = $this->getOwner();
		$test = $owner->getCurrentTest();
		if($test === null){
			if(!$owner->startNextTest()){
				$owner->getServer()->getScheduler()->cancelTask($this->getHandler()->getTaskId());
				$owner->onAllTestsCompleted();
			}
		}elseif($test->isFinished() or $test->isTimedOut()){
			$owner->onTestCompleted($test);
		}else{
			$test->tick();
		}
	}
}