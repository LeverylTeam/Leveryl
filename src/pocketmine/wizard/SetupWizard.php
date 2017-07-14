<?php

/*
 *
 *  ____			_		_   __  __ _				  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___	  |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|	 |_|  |_|_|
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

/**
 * Set-up wizard used on the first run
 * Can be disabled with --no-wizard
 */
namespace pocketmine\wizard;

use pocketmine\lang\BaseLang;
use pocketmine\utils\Config;
use pocketmine\utils\Utils;

class SetupWizard{
	const DEFAULT_NAME = "§l§f§oLeveryl§r§a MC:PE Server§r";
	const DEFAULT_PORT = 19132;
	const DEFAULT_MEMORY = 256;
	const DEFAULT_PLAYERS = 20;
	const DEFAULT_GAMEMODE = 0;

	/** @var BaseLang */
	private $lang;

	public function __construct(){

	}

	public function run(){
        $this->writeLine("     __                           _ ");
        $this->writeLine("    / /  _____   _____ _ __ _   _| |");
        $this->writeLine("   / /  / _ \ \ / / _ \ '__| | | | |");
        $this->writeLine("  / /__|  __/\ V /  __/ |  | |_| | |");
        $this->writeLine("  \____/\___| \_/ \___|_|   \__, |_|");
        $this->writeLine("                            |___/   ");
        $this->writeLine();
        $this->writeLine(" This program is free software: you can redistribute it and/or modify");
        $this->writeLine(" it under the terms of the GNU Lesser General Public License as published by");
        $this->writeLine(" the Free Software Foundation, either version 3 of the License, or");
        $this->writeLine(" (at your option) any later version.");
        $this->writeLine();
        $this->writeLine("----------------------------------------------------------------------------");
        $this->writeLine();
		$this->message("Leveryl set-up wizard");
        $this->notice("Press the [ENTER] Key to use the Default Value.");

		$langs = BaseLang::getLanguageList();
		if(empty($langs)){
			$this->error("No language files found, please use provided builds or clone the repository recursively.");
			return false;
		}

		$this->message("Please select a language");
		foreach($langs as $short => $native){
			$this->message(" $native => $short");
		}

		do{
			$lang = strtolower($this->getInput("Language", "eng"));
			if(!isset($langs[$lang])){
				$this->error("Couldn't find the language");
				$lang = null;
			}
		}while($lang === null);

		$this->lang = new BaseLang($lang);

		$this->message($this->lang->get("language_has_been_selected"));

		if(!$this->showLicense()){
			return false;
		}

		if(strtolower($this->getInput($this->lang->get("skip_installer"), "n", "y/N")) === "y"){
			return true;
		}

		$this->welcome();
		$this->generateBaseConfig();
		$this->generateUserFiles();

		$this->networkFunctions();

		$this->endWizard();

		return true;
	}

	private function showLicense(){
		$this->notice("Please accept the license below before you continue: ");
		$this->message(" ----- + The GNU LGPL License + ----- ");
        $this->message("This program is free software: you can redistribute it and/or modify");
        $this->message("it under the terms of the GNU Lesser General Public License as published by");
        $this->message("the Free Software Foundation, either version 3 of the License, or");
        $this->message("(at your option) any later version.");
        $this->message(" ----- + The GNU LGPL License + ----- ");
		if(strtolower($this->getInput($this->lang->get("accept_license"), "n", "y/N")) !== "y"){
			$this->error("You have to Accept the License before you continue.");
			sleep(5);
			return false;
		}

		return true;
	}

	private function welcome(){
		$this->message($this->lang->get("setting_up_server_now"));
		$this->message($this->lang->get("default_values_info"));
		$this->message($this->lang->get("server_properties"));
	}

	private function generateBaseConfig(){
		$config = new Config(\pocketmine\DATA . "server.properties", Config::PROPERTIES);

		$config->set("motd", ($name = $this->getInput($this->lang->get("name_your_server"), self::DEFAULT_NAME)));
		$config->set("server-name", $name);

		$this->message($this->lang->get("port_warning"));

		do{
			$port = (int) $this->getInput($this->lang->get("server_port"), (string) self::DEFAULT_PORT);
			if($port <= 0 or $port > 65535){
				$this->error($this->lang->get("invalid_port"));
				continue;
			}

			break;
		}while(true);
		$config->set("server-port", $port);

		$this->message($this->lang->get("gamemode_info"));

		do{
			$gamemode = (int) $this->getInput($this->lang->get("default_gamemode"), (string) self::DEFAULT_GAMEMODE);
		}while($gamemode < 0 or $gamemode > 3);
		$config->set("gamemode", $gamemode);

		$config->set("max-players", (int) $this->getInput($this->lang->get("max_players"), (string) self::DEFAULT_PLAYERS));

		$this->message($this->lang->get("spawn_protection_info"));

		if(strtolower($this->getInput($this->lang->get("spawn_protection"), "y", "Y/n")) === "n"){
			$config->set("spawn-protection", -1);
		}else{
			$config->set("spawn-protection", 16);
		}

		$config->save();
	}

	private function generateUserFiles(){
		$this->message($this->lang->get("op_info"));

		$op = strtolower($this->getInput($this->lang->get("op_who"), ""));
		if($op === ""){
			$this->error($this->lang->get("op_warning"));
		}else{
			$ops = new Config(\pocketmine\DATA . "ops.txt", Config::ENUM);
			$ops->set($op, true);
			$ops->save();
		}

		$this->message($this->lang->get("whitelist_info"));

		$config = new Config(\pocketmine\DATA . "server.properties", Config::PROPERTIES);
		if(strtolower($this->getInput($this->lang->get("whitelist_enable"), "n", "y/N")) === "y"){
			$this->error($this->lang->get("whitelist_warning"));
			$config->set("white-list", true);
		}else{
			$config->set("white-list", false);
		}
		$config->save();
	}

	private function networkFunctions(){
		$config = new Config(\pocketmine\DATA . "server.properties", Config::PROPERTIES);
		$this->error($this->lang->get("query_warning1"));
		$this->error($this->lang->get("query_warning2"));
		if(strtolower($this->getInput($this->lang->get("query_disable"), "n", "y/N")) === "y"){
			$config->set("enable-query", false);
		}else{
			$config->set("enable-query", true);
		}

		$this->message($this->lang->get("rcon_info"));
		if(strtolower($this->getInput($this->lang->get("rcon_enable"), "n", "y/N")) === "y"){
			$config->set("enable-rcon", true);
			$password = substr(base64_encode(random_bytes(20)), 3, 10);
			$config->set("rcon.password", $password);
			$this->message($this->lang->get("rcon_password") . ": " . $password);
		}else{
			$config->set("enable-rcon", false);
		}

		$config->save();


		$this->message($this->lang->get("ip_get"));

		$externalIP = Utils::getIP();
		if($externalIP === false){
			$externalIP = "unknown (server offline)";
		}
		$internalIP = gethostbyname(trim(`hostname`));

		$this->notice($this->lang->translateString("ip_warning", ["EXTERNAL_IP" => $externalIP, "INTERNAL_IP" => $internalIP]));
		$this->notice($this->lang->get("ip_confirm"));
		$this->readLine();
	}

	private function endWizard(){
		$this->message($this->lang->get("you_have_finished"));
		$this->message($this->lang->get("pocketmine_will_start"));
	}

	private function writeLine(string $line = ""){
		echo $line . PHP_EOL;
	}

	private function readLine() : string{
		return trim((string) fgets(STDIN));
	}

	private function message(string $message){
		$this->writeLine(date("H:i:s", time()) . " [INFO] " . $message);
	}

	private function error(string $message){
		$this->writeLine(date("H:i:s", time()) . " [ERROR] " .  $message);
	}

	private function notice(string $message){
		$this->writeLine(date("H:i:s", time()) . " [NOTICE] " .  $message);
	}

	private function getInput(string $message, string $default = "", string $options = ""){
		$message = date("H:i:s", time()) . " [INPUT] " . $message;

		if($options !== "" or $default !== ""){
		    if($default == self::DEFAULT_NAME){
                $message .= " (" . ($options === "" ? "Leveryl MC:PE Server" : $options) . ")";
            } else {
                $message .= " (" . ($options === "" ? $default : $options) . ")";
            }
		}
		$message .= ": ";

		echo $message;

		$input = $this->readLine();

		return $input === "" ? $default : $input;
	}


}
