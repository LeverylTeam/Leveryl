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
*/

namespace pocketmine\command\defaults;

use pocketmine\command\CommandSender;
use pocketmine\level\generator\biome\Biome;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class BiomeCommand extends VanillaCommand
{

	/**
	 * ClearCommand constructor.
	 * @param string $name
	 */
	public function __construct($name)
	{
		parent::__construct($name);
		$this->setPermission("pocketmine.command.biome");
		$this->setDescription("Get Biome Information on Current Chunk");
	}

	/**
	 * @param CommandSender $sender
	 * @param string $currentAlias
	 * @param array $args
	 * @return bool
	 */
	public function execute(CommandSender $sender, $currentAlias, array $args)
	{
		if(!$this->testPermission($sender)) {
			return true;
		}
		if($sender instanceof Player) {
			$level = $sender->getLevel();
			$x = intval($sender->getX());
			$z = intval($sender->getZ());
			$id = $level->getBiomeId($x, $z);
			$biome = Biome::getBiome($id);
			$name = $biome->getName();
			$sender->sendMessage("Biome ID: " . $id);
			$sender->sendMessage("Biome Name: " . $name);
		} else {
			$sender->sendMessage("Please run this command in-game.");
		}

		return true;
	}
}