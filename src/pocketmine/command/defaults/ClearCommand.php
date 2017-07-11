<?php

// @Team Tesseract
// @Author Rateek
// Fixed and Made better by @CortexPE - LeverylTeam :P

namespace pocketmine\command\defaults;

use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class ClearCommand extends VanillaCommand {

    /**
     * ClearCommand constructor.
     * @param string $name
     */
    public function __construct($name){
        parent::__construct($name);
        $this->setPermission("pocketmine.command.clear");
    }

    /**
     * @param CommandSender $sender
     * @param string $currentAlias
     * @param array $args
     * @return bool
     */
    public function execute(CommandSender $sender, $currentAlias, array $args){
        if(!$this->testPermission($sender)){
            return true;
        }
        if($sender instanceof Player){
            $sender->getInventory()->clearAll();
            $sender->sendMessage(TextFormat::GREEN . "Successfully cleared your inventory");
        }else{
            $sender->sendMessage(TextFormat::RED . "Please run this command in-game.");
        }
        return true;
    }
}