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

namespace pocketmine\command\defaults;

use pocketmine\command\CommandSender;
use pocketmine\event\TranslationContainer;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class GameRuleCommand extends VanillaCommand
{

    public function __construct($name)
    {
        parent::__construct(
            $name,
            "%pocketmine.command.gamerule.description",
            "%pocketmine.command.gamerule.usage"
        );
        $this->setPermission("pocketmine.command.gamerule");
    }

    public function execute(CommandSender $sender, $currentAlias, array $args)
    {
        if (!$this->testPermission($sender)) {
            return true;
        }
        if (!isset($args[0], $args[1])) {
            $sender->sendMessage(new TranslationContainer("commands.generic.usage", [$this->usageMessage]));
        } else {
            switch($args[0]){

                case "keepInventory":
                    if(in_array(strtolower($args[1]), array("true", "false"))){
                        if($sender instanceof Player){
                            $sender->getLevel()->updateGameRule($args[0], $args[1]);
                            $sender->sendMessage(new TranslationContainer("gamerule.change.success", [$args[0], $args[1]]));
                        } else {
                            $sender->getServer()->getDefaultLevel()->updateGameRule($args[0], $args[1]);
                            $sender->sendMessage(new TranslationContainer("gamerule.change.success", [$args[0], $args[1]]));
                        }
                    } else {
                        $sender->sendMessage(new TranslationContainer("commands.generic.usage", ["%pocketmine.command.gamerule.argrument"]));
                    }
                    break;

                case "showDeathMessages":
                    if(in_array(strtolower($args[1]), array("true", "false"))){
                        if($sender instanceof Player){
                            $sender->getLevel()->updateGameRule($args[0], $args[1]);
                            $sender->sendMessage(new TranslationContainer("gamerule.change.success", [$args[0], $args[1]]));
                        } else {
                            $sender->getServer()->getDefaultLevel()->updateGameRule($args[0], $args[1]);
                            $sender->sendMessage(new TranslationContainer("gamerule.change.success", [$args[0], $args[1]]));
                        }
                    } else {
                        $sender->sendMessage(new TranslationContainer("commands.generic.usage", ["%pocketmine.command.gamerule.argrument"]));
                    }
                    break;

                case "doTileDrops":
                    if(in_array(strtolower($args[1]), array("true", "false"))){
                        if($sender instanceof Player){
                            $sender->getLevel()->updateGameRule($args[0], $args[1]);
                            $sender->sendMessage(new TranslationContainer("gamerule.change.success", [$args[0], $args[1]]));
                        } else {
                            $sender->getServer()->getDefaultLevel()->updateGameRule($args[0], $args[1]);
                            $sender->sendMessage(new TranslationContainer("gamerule.change.success", [$args[0], $args[1]]));
                        }
                    } else {
                        $sender->sendMessage(new TranslationContainer("commands.generic.usage", ["%pocketmine.command.gamerule.argrument"]));
                    }
                    break;

                case "doFireTick":
                    if(in_array(strtolower($args[1]), array("true", "false"))){
                        if($sender instanceof Player){
                            $sender->getLevel()->updateGameRule($args[0], $args[1]);
                            $sender->sendMessage(new TranslationContainer("gamerule.change.success", [$args[0], $args[1]]));
                        } else {
                            $sender->getServer()->getDefaultLevel()->updateGameRule($args[0], $args[1]);
                            $sender->sendMessage(new TranslationContainer("gamerule.change.success", [$args[0], $args[1]]));
                        }
                    } else {
                        $sender->sendMessage(new TranslationContainer("commands.generic.usage", ["%pocketmine.command.gamerule.argrument"]));
                    }
                    break;

                case "doDaylightCycle":
                    if(in_array(strtolower($args[1]), array("true", "false"))){
                        if($sender instanceof Player){
                            $sender->getLevel()->updateGameRule($args[0], $args[1]);
                            $sender->sendMessage(new TranslationContainer("gamerule.change.success", [$args[0], $args[1]]));
                        } else {
                            $sender->getServer()->getDefaultLevel()->updateGameRule($args[0], $args[1]);
                            $sender->sendMessage(new TranslationContainer("gamerule.change.success", [$args[0], $args[1]]));
                        }
                    } else {
                        $sender->sendMessage(new TranslationContainer("commands.generic.usage", ["%pocketmine.command.gamerule.argrument"]));
                    }
                    break;

            }
        }
    }
}