<?php

namespace pocketmine\command\defaults;

use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\ProtocolInfo as Info;
use pocketmine\Server;

class MakeServerCommand extends VanillaCommand
{

	public function __construct($name)
	{
		parent::__construct(
			$name,
			"Creates a Server Phar plugin from non-packaged installation",
			"/makeserver <pluginName> (nogz)",
			["ms"]
		);
		$this->setPermission("pocketmine.command.makeserver");
	}

	public function execute(CommandSender $sender, $commandLabel, array $args)
	{
		if(!$this->testPermission($sender)) {
			return false;
		}

		$server = $sender->getServer();
		if(!file_exists(Server::getInstance()->getPluginPath() . "Leveryl" . DIRECTORY_SEPARATOR)) {
			mkdir(Server::getInstance()->getPluginPath() . "Leveryl" . DIRECTORY_SEPARATOR, 0777);
		}
		$pharPath = Server::getInstance()->getPluginPath() . "Leveryl" . DIRECTORY_SEPARATOR . "Leveryl.phar";
		if(file_exists($pharPath)) {
			$sender->sendMessage("[LDV] Phar file already exists, overwriting...");
			@unlink($pharPath);
		}
		$phar = new \Phar($pharPath);
		$phar->setMetadata([
			"name"         => $server->getName(),
			"version"      => $server->getPocketMineVersion(),
			"api"          => $server->getApiVersion(),
			"minecraft"    => $server->getVersion(),
			"protocol"     => Info::CURRENT_PROTOCOL,
			"creationDate" => time(),
		]);
		$phar->setStub('<?php define("pocketmine\\\\PATH", "phar://". __FILE__ ."/"); require_once("phar://". __FILE__ ."/src/pocketmine/PocketMine.php");  __HALT_COMPILER();');
		$phar->setSignatureAlgorithm(\Phar::SHA1);
		$phar->startBuffering();

		$filePath = substr(\pocketmine\PATH, 0, 7) === "phar://" ? \pocketmine\PATH : realpath(\pocketmine\PATH) . "/";
		$filePath = rtrim(str_replace("\\", "/", $filePath), "/") . "/";
		if(is_dir($filePath . ".git")){
			// Add some Git files as they are required in getting GIT_COMMIT
			foreach(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($filePath . ".git")) as $file){
				$path = ltrim(str_replace(["\\", $filePath], ["/", ""], $file), "/");
				if((strpos($path, ".git/HEAD") === false and strpos($path, ".git/refs/heads") === false) or strpos($path, "/.") !== false){
					continue;
				}
				$phar->addFile($file, $path);
				$sender->sendMessage("[LDV] Adding $path");
			}
		}
		foreach(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($filePath . "src")) as $file) {
			$path = ltrim(str_replace(["\\", $filePath], ["/", ""], $file), "/");
			if($path{0} === "." or strpos($path, "/.") !== false or substr($path, 0, 4) !== "src/") {
				continue;
			}
			$phar->addFile($file, $path);
			$sender->sendMessage("[LDV] Adding $path");
		}
		foreach($phar as $file => $finfo) {
			/** @var \PharFileInfo $finfo */
			if($finfo->getSize() > (1024 * 512)) {
				$finfo->compress(\Phar::GZ);
			}
		}
		if(!isset($args[0]) or (isset($args[0]) and $args[0] != "nogz")) {
			$phar->compressFiles(\Phar::GZ);
		}
		$phar->stopBuffering();

		$sender->sendMessage("[LDV] " . $server->getName() . " " . $server->getPocketMineVersion() . " Phar file has been created on " . $pharPath);

		return true;
	}
}
