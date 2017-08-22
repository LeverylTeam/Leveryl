<?php

/*
 *     __						    _
 *    / /  _____   _____ _ __ _   _| |
 *   / /  / _ \ \ / / _ \ '__| | | | |
 *  / /__|  __/\ V /  __/ |  | |_| | |
 *  \____/\___| \_/ \___|_|   \__, |_|
 *						      |___/
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author LeverylTeam
 * @link https://github.com/LeverylTeam
 *
 * TODO: Make this Async... (If possible in the future) ~ 6/29/2017
 *
*/

declare(strict_types = 1);

namespace pocketmine\command\defaults;

use pocketmine\command\CommandSender;
use pocketmine\level\generator\Generator;
use pocketmine\utils\TextFormat;

class CreateWorldCommand extends VanillaCommand {

	public function __construct($name){
		parent::__construct(
			$name,
			"Generate a world",
			"/createworld <world name> [seed] [generator]"
		);
		$this->setPermission("pocketmine.command.createworld");
	}

	public function execute(CommandSender $sender, $senderurrentAlias, array $args){
		if(!$this->testPermission($sender)){
			return true;
		}

		if(count($args) < 1 || count($args) > 3){
			$sender->sendMessage("USAGE: /createworld <world name> [seed] [generator]");

			return false;
		}
		$world = array_shift($args);

		if($sender->getServer()->isLevelGenerated($world)){
			$sender->sendMessage(TextFormat::RED . "A world named " . $args[0] . " already exists");

			return true;
		}

		$seed = null;
		$generator = null;
		if(isset($args[1])) $seed = intval($args[1]);
		if(isset($args[2])){
			$generator = Generator::getGenerator($args[2]);
			if(strtolower($args[2]) != Generator::getGeneratorName($generator)){
				$sender->sendMessage(TextFormat::RED . "Unknown generator: " . $args[2]);

				return true;
			}
			$sender->sendMessage(TextFormat::GREEN . "Generating \"" . $args[0] . "\" using Generator: " . Generator::getGeneratorName($generator));
		}
		$sender->sendMessage(TextFormat::YELLOW . "Generating Level: " . $world);
		$sender->getServer()->generateLevel($world, $seed, $generator);
		$sender->sendMessage(TextFormat::YELLOW . "Level: \"" . $world . "\" Has now been generated.");
		$sender->sendMessage(TextFormat::GREEN . "Loading Generated Level: " . $world);
		if($sender->getServer()->loadLevel($world)){
			$sender->sendMessage(TextFormat::GREEN . "Successfully Loaded Level: " . $world);
		}else{
			$sender->sendMessage(TextFormat::RED . "Failed Loading Level: " . $world);
		}

		return true;
	}
}
