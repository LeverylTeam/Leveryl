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

declare(strict_types=1);

namespace pocketmine\command\defaults;

use pocketmine\command\CommandSender;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class WorldCommand extends VanillaCommand
{

    public function __construct($name)
    {
        parent::__construct(
            $name,
            "Teleport to a world",
            "/world [target player] <world name>"
        );
        $this->setPermission("pocketmine.command.world");
    }

    public function execute(CommandSender $sender, $currentAlias, array $args)
    {
        if (!$this->testPermission($sender)) {
            return true;
        }

        if ($sender instanceof Player) {
            if (count($args) == 1) {
                if (($level = $sender->getServer()->getLevelByName($args[0]))) {
                    $pos = $level->getSpawnLocation();
                    $sender->teleport(new Position($pos->getX(), $pos->getY(), $pos->getZ(), $level));
                    $sender->sendMessage("Teleported to Level: " . $level->getName());
                    return true;
                } else {
                    $sender->sendMessage(TextFormat::RED . "World: \"" . $args[0] . "\" Does not exist or is not Loaded");
                    return false;
                }
            } elseif (count($args) > 1 && count($args) < 3) {
                if (($level = $sender->getServer()->getLevelByName($args[1]))) {
                    $player = $sender->getServer()->getPlayer($args[0]);
                    $pos = $level->getSpawnLocation();
                    $player->teleport(new Position($pos->getX(), $pos->getY(), $pos->getZ(), $level));
                    $player->sendMessage("Teleported to Level: " . $level->getName());
                    return true;
                } else {
                    $sender->sendMessage(TextFormat::RED . "World: \"" . $args[1] . "\" Does not exist or is not Loaded");
                    return false;
                }
            } else {
                $sender->sendMessage("Usage: /world [target player] <world name>");
                return false;
            }
        } else {
            $sender->sendMessage(TextFormat::RED . "This command must be executed as a player");
            return false;
        }
    }
}
