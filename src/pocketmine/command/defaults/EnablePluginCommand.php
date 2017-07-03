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
use pocketmine\utils\TextFormat;

class EnablePluginCommand extends VanillaCommand
{

    public function __construct($name)
    {
        parent::__construct(
            $name,
            "Enables a plugin",
            "/enableplugin <PluginName>",
            ["enp"]
        );
        $this->setPermission("pocketmine.command.enableplugin");
    }

    public function execute(CommandSender $sender, $currentAlias, array $args)
    {
        if (!$this->testPermission($sender)) {
            return true;
        }
        if (count($args) <= 0) {
            $sender->sendMessage("Usage: /enableplugin <PluginName>");
            return false;
        }
        $p = $sender->getServer()->getPluginManager()->getPlugin($args[0]);
        if ($p == null) {
            $sender->sendMessage(TextFormat::RED . "Could not find the specific plugin.");
            return false;
        }
        if ($p->isEnabled()) {
            $sender->sendMessage(TextFormat::RED . "Plugin is already enabled.");
            return false;
        }
        $sender->getServer()->getPluginManager()->enablePlugin($p);
        $sender->sendMessage(TextFormat::GREEN . "Plugin \"" . TextFormat::AQUA . $p->getName() . TextFormat::GREEN . "\" has been successfully enabled.");
        return true;
    }
}