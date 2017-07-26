<?php

// @Team Tesseract
// @Author Rateek
// Fixed and Made better by @CortexPE - @LeverylTeam :P

namespace pocketmine\command\defaults;

use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class ClearCommand extends VanillaCommand
{

	/**
	 * ClearCommand constructor.
	 * @param string $name
	 */
	public function __construct($name)
	{
		parent::__construct($name);
		$this->setPermission("pocketmine.command.clear");
		$this->setDescription("commands.clear.description");
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
			$pname = $sender->getName();
			$c = 0;
			foreach($sender->getInventory()->getContents() as $itm){
				$c = $c + $itm->getCount();
			}
			$sender->getInventory()->clearAll();
			$sender->sendMessage("Cleared the inventory of " . $pname . ", removing " . $c . " items");
		} else {
			$sender->sendMessage("Please run this command in-game.");
		}

		return true;
	}
}