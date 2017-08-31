<?php

namespace pocketmine\command\defaults;

use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use pocketmine\utils\Terminal;
use pocketmine\utils\TextFormat;

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

		$meta = [
			"name"         => $sender->getServer()->getName(),
			"version"      => $sender->getServer()->getPocketMineVersion(),
			"api"          => $sender->getServer()->getApiVersion(),
			"minecraft"    => $sender->getServer()->getVersion(),
			"protocol"     => ProtocolInfo::CURRENT_PROTOCOL,
			"creationDate" => time(),
		];

		$verbose = true;

		$sender->getServer()->getScheduler()->scheduleAsyncTask(new MakeServerTask($meta, Server::getInstance()->getPluginPath(), $verbose));

		return true;
	}
}

class MakeServerTask extends AsyncTask {

	private $meta;
	private $verbose;
	private $pharPath, $serverPath;

	public function __construct(array $metadata, string $serverPath, bool $verbose = true){
		$this->meta = $metadata;
		$this->serverPath = $serverPath;
		$this->pharPath = $serverPath . "Leveryl" . DIRECTORY_SEPARATOR . "Leveryl.phar";
		$this->verbose = $verbose;
	}

	public  function onRun(){
		if(!file_exists($this->serverPath . "Leveryl" . DIRECTORY_SEPARATOR)) {
			mkdir($this->serverPath . "Leveryl" . DIRECTORY_SEPARATOR, 0777);
		}
		if(file_exists($this->pharPath)) {
			if($this->verbose){
				$this->logMsg("Phar file already exists, overwriting...", "LeverylDevTools", TextFormat::YELLOW);
			}
			@unlink($this->pharPath);
		}
		/** @var \Phar */
		$phar = new \Phar($this->pharPath);
		$phar->setMetadata($this->meta);
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
				if($this->verbose){
					$this->logMsg("Adding " . $path, "LeverylDevTools", TextFormat::YELLOW);
				}
			}
		}
		foreach(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($filePath . "src")) as $file) {
			$path = ltrim(str_replace(["\\", $filePath], ["/", ""], $file), "/");
			if($path{0} === "." or strpos($path, "/.") !== false or substr($path, 0, 4) !== "src/") {
				continue;
			}
			$phar->addFile($file, $path);
			if($this->verbose){
				$this->logMsg("Adding " . $path, "LeverylDevTools", TextFormat::YELLOW);
			}
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

		return true;
	}

	public function onCompletion(Server $server){
		if($this->verbose){
			$this->logMsg("Leveryl.phar has been created on: " . $this->pharPath, "LeverylDevTools", TextFormat::YELLOW);
		}
	}

	private function logMsg(string $message, string $name, string $color){
		$now = time();
		$message = TextFormat::toANSI(TextFormat::AQUA . date("H:i:s", $now) . " " . TextFormat::RESET . $color . "[" . $name . "] " . TextFormat::WHITE . $message . TextFormat::RESET);
		$cleanMessage = TextFormat::clean($message);

		if(!Terminal::hasFormattingCodes()){
			echo $cleanMessage . PHP_EOL;
		}else{
			echo $message . PHP_EOL;
		}
	}
}
