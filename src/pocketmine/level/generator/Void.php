<?php

/*
 *
 *  _____   _____   __   _   _   _____  __    __  _____
 * /  ___| | ____| |  \ | | | | /  ___/ \ \  / / /  ___/
 * | |     | |__   |   \| | | | | |___   \ \/ /  | |___
 * | |  _  |  __|  | |\   | | | \___  \   \  /   \___  \
 * | |_| | | |___  | | \  | | |  ___| |   / /     ___| |
 * \_____/ |_____| |_|  \_| |_| /_____/  /_/     /_____/
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author iTX Technologies
 * @link https://itxtech.org
 *
 */

namespace pocketmine\level\generator;

use pocketmine\block\Block;
use pocketmine\level\ChunkManager;
use pocketmine\level\format\Chunk;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;

class Void extends Generator{
	/** @var ChunkManager */
	private $level;
	/** @var Chunk */
	private $chunk;
	/** @var Random */
	private $random;
	private $options;
	/** @var Chunk */
	private $emptyChunk = null;

	const SPAWN_X = 256;
	const SPAWN_Y = 70;
	const SPAWN_Z = 256;

	public function getSettings(){
		return [];
	}

	public function getName(){
		return "Void";
	}

	public function __construct(array $settings = []){
		$this->options = $settings;
	}

	public function init(ChunkManager $level, Random $random){
		$this->level = $level;
		$this->random = $random;
	}

	public function generateChunk($chunkX, $chunkZ){
		if($this->emptyChunk === null){
			$this->chunk = clone $this->level->getChunk($chunkX, $chunkZ);
			$this->chunk->setGenerated();

			for($Z = 0; $Z < 16; ++$Z){
				for($X = 0; $X < 16; ++$X){
					$this->chunk->setBiomeId($X, $Z, 1);
					for($y = 0; $y < Level::Y_MAX; ++$y){
						$this->chunk->setBlockId($X, $y, $Z, Block::AIR);
					}
				}
			}

			$spawn = $this->getSpawn();
			if($spawn->getX() >> 4 === $chunkX and $spawn->getZ() >> 4 === $chunkZ){
				$this->chunk->setBlockId(self::SPAWN_X, self::SPAWN_Y - 1, self::SPAWN_Z, Block::GRASS);
			}else{
				$this->emptyChunk = clone $this->chunk;
			}
		}else{
			$this->chunk = clone $this->emptyChunk;
		}

		$chunk = clone $this->chunk;
		$chunk->setX($chunkX);
		$chunk->setZ($chunkZ);
		$this->level->setChunk($chunkX, $chunkZ, $chunk);
	}

	public function populateChunk($chunkX, $chunkZ){

	}

	public function getSpawn(){
		return new Vector3(self::SPAWN_X, self::SPAWN_Y, self::SPAWN_Z);
	}

}