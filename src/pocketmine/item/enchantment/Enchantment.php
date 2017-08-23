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

namespace pocketmine\item\enchantment;

use pocketmine\item\ChainBoots;
use pocketmine\item\ChainChestplate;
use pocketmine\item\ChainHelmet;
use pocketmine\item\ChainLeggings;
use pocketmine\item\DiamondAxe;
use pocketmine\item\DiamondBoots;
use pocketmine\item\DiamondChestplate;
use pocketmine\item\DiamondHelmet;
use pocketmine\item\DiamondHoe;
use pocketmine\item\DiamondLeggings;
use pocketmine\item\DiamondPickaxe;
use pocketmine\item\DiamondShovel;
use pocketmine\item\DiamondSword;
use pocketmine\item\GoldAxe;
use pocketmine\item\GoldBoots;
use pocketmine\item\GoldChestplate;
use pocketmine\item\GoldHelmet;
use pocketmine\item\GoldHoe;
use pocketmine\item\GoldLeggings;
use pocketmine\item\GoldPickaxe;
use pocketmine\item\GoldShovel;
use pocketmine\item\GoldSword;
use pocketmine\item\IronAxe;
use pocketmine\item\IronBoots;
use pocketmine\item\IronChestplate;
use pocketmine\item\IronHelmet;
use pocketmine\item\IronHoe;
use pocketmine\item\IronLeggings;
use pocketmine\item\IronPickaxe;
use pocketmine\item\IronShovel;
use pocketmine\item\IronSword;
use pocketmine\item\Item;
use pocketmine\item\LeatherBoots;
use pocketmine\item\LeatherCap;
use pocketmine\item\LeatherPants;
use pocketmine\item\LeatherTunic;
use pocketmine\item\StoneAxe;
use pocketmine\item\StoneHoe;
use pocketmine\item\StonePickaxe;
use pocketmine\item\StoneShovel;
use pocketmine\item\StoneSword;
use pocketmine\item\WoodenAxe;
use pocketmine\item\WoodenHoe;
use pocketmine\item\WoodenPickaxe;
use pocketmine\item\WoodenShovel;
use pocketmine\item\WoodenSword;
use pocketmine\Server;

class Enchantment {

	const TYPE_INVALID = -1;

	const PROTECTION = 0;
	const FIRE_PROTECTION = 1;
	const FEATHER_FALLING = 2;
	const BLAST_PROTECTION = 3;
	const PROJECTILE_PROTECTION = 4;
	const THORNS = 5;
	const RESPIRATION = 6;
	const DEPTH_STRIDER = 7;
	const AQUA_AFFINITY = 8;
	const SHARPNESS = 9;
	const SMITE = 10;
	const BANE_OF_ARTHROPODS = 11;
	const KNOCKBACK = 12;
	const FIRE_ASPECT = 13;
	const LOOTING = 14;
	const EFFICIENCY = 15;
	const SILK_TOUCH = 16;
	const UNBREAKING = 17;
	const FORTUNE = 18;
	const POWER = 19;
	const PUNCH = 20;
	const FLAME = 21;
	const INFINITY = 22;
	const LUCK_OF_THE_SEA = 23;
	const LURE = 24;

	const RARITY_COMMON = 0;
	const RARITY_UNCOMMON = 1;
	const RARITY_RARE = 2;
	const RARITY_MYTHIC = 3;

	const ACTIVATION_EQUIP = 0;
	const ACTIVATION_HELD = 1;
	const ACTIVATION_SELF = 2;

	const SLOT_NONE = 0;
	const SLOT_ALL = 0b11111111111111;
	const SLOT_ARMOR = 0b1111;
	const SLOT_HEAD = 0b1;
	const SLOT_TORSO = 0b10;
	const SLOT_LEGS = 0b100;
	const SLOT_FEET = 0b1000;
	const SLOT_SWORD = 0b10000;
	const SLOT_BOW = 0b100000;
	const SLOT_TOOL = 0b111000000;
	const SLOT_HOE = 0b1000000;
	const SLOT_SHEARS = 0b10000000;
	const SLOT_FLINT_AND_STEEL = 0b10000000;
	const SLOT_DIG = 0b111000000000;
	const SLOT_AXE = 0b1000000000;
	const SLOT_PICKAXE = 0b10000000000;
	const SLOT_SHOVEL = 0b10000000000;
	const SLOT_FISHING_ROD = 0b100000000000;
	const SLOT_CARROT_STICK = 0b1000000000000;

	public static $words = ["the", "elder", "scrolls", "klaatu", "berata", "niktu", "xyzzy", "bless", "curse", "light", "darkness", "fire", "air",
		"earth", "water", "hot", "dry", "cold", "wet", "ignite", "snuff", "embiggen", "twist", "shorten", "stretch", "fiddle", "destroy", "imbue", "galvanize",
		"enchant", "free", "limited", "range", "of", "towards", "inside", "sphere", "cube", "self", "other", "ball", "mental", "physical", "grow", "shrink",
		"demon", "elemental", "spirit", "animal", "creature", "beast", "humanoid", "undead", "fresh", "stale"];


	/** @var Enchantment[] */
	protected static $enchantments;

	public static function init(){
		self::$enchantments = new \SplFixedArray(256);

		self::$enchantments[self::PROTECTION] = new Enchantment(self::PROTECTION, "%enchantment.protect.all", self::RARITY_COMMON, self::ACTIVATION_EQUIP, self::SLOT_ARMOR);
		self::$enchantments[self::FIRE_PROTECTION] = new Enchantment(self::FIRE_PROTECTION, "%enchantment.protect.fire", self::RARITY_UNCOMMON, self::ACTIVATION_EQUIP, self::SLOT_ARMOR);
		self::$enchantments[self::FEATHER_FALLING] = new Enchantment(self::FEATHER_FALLING, "%enchantment.protect.fall", self::RARITY_UNCOMMON, self::ACTIVATION_EQUIP, self::SLOT_FEET);

		self::$enchantments[self::BLAST_PROTECTION] = new Enchantment(self::BLAST_PROTECTION, "%enchantment.protect.explosion", self::RARITY_UNCOMMON, self::ACTIVATION_EQUIP, self::SLOT_ARMOR);
		self::$enchantments[self::PROJECTILE_PROTECTION] = new Enchantment(self::PROJECTILE_PROTECTION, "%enchantment.protect.projectile", self::RARITY_UNCOMMON, self::ACTIVATION_EQUIP, self::SLOT_ARMOR);
		self::$enchantments[self::THORNS] = new Enchantment(self::THORNS, "%enchantment.protect.thorns", self::RARITY_UNCOMMON, self::ACTIVATION_EQUIP, self::SLOT_SWORD);
		self::$enchantments[self::RESPIRATION] = new Enchantment(self::RESPIRATION, "%enchantment.protect.waterbrething", self::RARITY_UNCOMMON, self::ACTIVATION_EQUIP, self::SLOT_FEET);
		self::$enchantments[self::DEPTH_STRIDER] = new Enchantment(self::DEPTH_STRIDER, "%enchantment.waterspeed", self::RARITY_UNCOMMON, self::ACTIVATION_EQUIP, self::SLOT_FEET);
		self::$enchantments[self::AQUA_AFFINITY] = new Enchantment(self::AQUA_AFFINITY, "%enchantment.protect.wateraffinity", self::RARITY_UNCOMMON, self::ACTIVATION_EQUIP, self::SLOT_FEET);

		self::$enchantments[self::SHARPNESS] = new Enchantment(self::SHARPNESS, "%enchantment.weapon.sharpness", self::RARITY_UNCOMMON, self::ACTIVATION_EQUIP, self::SLOT_SWORD);
		self::$enchantments[self::SMITE] = new Enchantment(self::SMITE, "%enchantment.weapon.smite", self::RARITY_UNCOMMON, self::ACTIVATION_EQUIP, self::SLOT_SWORD);
		self::$enchantments[self::BANE_OF_ARTHROPODS] = new Enchantment(self::BANE_OF_ARTHROPODS, "%enchantment.weapon.arthropods", self::RARITY_UNCOMMON, self::ACTIVATION_EQUIP, self::SLOT_SWORD);
		self::$enchantments[self::KNOCKBACK] = new Enchantment(self::KNOCKBACK, "%enchantment.weapon.knockback", self::RARITY_UNCOMMON, self::ACTIVATION_EQUIP, self::SLOT_SWORD);
		self::$enchantments[self::FIRE_ASPECT] = new Enchantment(self::FIRE_ASPECT, "%enchantment.weapon.fireaspect", self::RARITY_UNCOMMON, self::ACTIVATION_EQUIP, self::SLOT_SWORD);
		self::$enchantments[self::LOOTING] = new Enchantment(self::LOOTING, "%enchantment.weapon.looting", self::RARITY_UNCOMMON, self::ACTIVATION_EQUIP, self::SLOT_SWORD);
		self::$enchantments[self::EFFICIENCY] = new Enchantment(self::EFFICIENCY, "%enchantment.mining.efficiency", self::RARITY_UNCOMMON, self::ACTIVATION_EQUIP, self::SLOT_TOOL);
		self::$enchantments[self::SILK_TOUCH] = new Enchantment(self::SILK_TOUCH, "%enchantment.mining.silktouch", self::RARITY_UNCOMMON, self::ACTIVATION_EQUIP, self::SLOT_TOOL);
		self::$enchantments[self::UNBREAKING] = new Enchantment(self::UNBREAKING, "%enchantment.mining.durability", self::RARITY_UNCOMMON, self::ACTIVATION_EQUIP, self::SLOT_TOOL);
		self::$enchantments[self::FORTUNE] = new Enchantment(self::FORTUNE, "%enchantment.mining.fortune", self::RARITY_UNCOMMON, self::ACTIVATION_EQUIP, self::SLOT_TOOL);
		self::$enchantments[self::POWER] = new Enchantment(self::POWER, "%enchantment.bow.power", self::RARITY_UNCOMMON, self::ACTIVATION_EQUIP, self::SLOT_BOW);
		self::$enchantments[self::PUNCH] = new Enchantment(self::PUNCH, "%enchantment.bow.knockback", self::RARITY_UNCOMMON, self::ACTIVATION_EQUIP, self::SLOT_BOW);
		self::$enchantments[self::FLAME] = new Enchantment(self::FLAME, "%enchantment.bow.flame", self::RARITY_UNCOMMON, self::ACTIVATION_EQUIP, self::SLOT_BOW);
		self::$enchantments[self::INFINITY] = new Enchantment(self::INFINITY, "%enchantment.bow.infinity", self::RARITY_UNCOMMON, self::ACTIVATION_EQUIP, self::SLOT_BOW);
		self::$enchantments[self::LUCK_OF_THE_SEA] = new Enchantment(self::LUCK_OF_THE_SEA, "%enchantment.fishing.fortune", self::RARITY_UNCOMMON, self::ACTIVATION_EQUIP, self::SLOT_FISHING_ROD);
		self::$enchantments[self::LURE] = new Enchantment(self::LURE, "%enchantment.fishing.lure", self::RARITY_UNCOMMON, self::ACTIVATION_EQUIP, self::SLOT_FISHING_ROD);

	}

	/**
	 * @param int $id
	 * @return $this
	 */
	public static function getEnchantment(int $id){
		if(isset(self::$enchantments[$id])){
			return clone self::$enchantments[(int)$id];
		}

		return new Enchantment(self::TYPE_INVALID, "unknown", 0, 0, 0);
	}

	public static function getEffectByName(String $name){
		if(defined(Enchantment::class . "::" . strtoupper($name))){
			return self::getEnchantment(constant(Enchantment::class . "::" . strtoupper($name)));
		}

		return null;
	}

	public static function registerEnchantment(int $id, String $name, int $rarity, int $activationType, int $slot){
		if(isset(self::$enchantments[$id])){
			Server::getInstance()->getLogger()->debug("Unable to register enchantment with id $id.");

			return new Enchantment(self::TYPE_INVALID, "unknown", 0, 0, 0);
		}
		self::$enchantments[$id] = new Enchantment($id, $name, $rarity, $activationType, $slot);

		return new Enchantment($id, $name, $rarity, $activationType, $slot);
	}


	public static function getEnchantmentByName(String $name){
		if(defined(Enchantment::class . "::" . strtoupper($name))){
			return self::getEnchantment(constant(Enchantment::class . "::" . strtoupper($name)));
		}

		return null;
	}

	public static function getEnchantAbility(Item $item){
		switch($item->getId()){
			case Item::BOOK:
			case Item::BOW:
			case Item::FISHING_ROD:
				return 4;
		}

		if($item->isArmor()){
			if($item instanceof ChainBoots or $item instanceof ChainChestplate or $item instanceof ChainHelmet or $item instanceof ChainLeggings) return 12;
			if($item instanceof IronBoots or $item instanceof IronChestplate or $item instanceof IronHelmet or $item instanceof IronLeggings) return 9;
			if($item instanceof DiamondBoots or $item instanceof DiamondChestplate or $item instanceof DiamondHelmet or $item instanceof DiamondLeggings) return 10;
			if($item instanceof LeatherBoots or $item instanceof LeatherTunic or $item instanceof LeatherCap or $item instanceof LeatherPants) return 15;
			if($item instanceof GoldBoots or $item instanceof GoldChestplate or $item instanceof GoldHelmet or $item instanceof GoldLeggings) return 25;
		}

		if($item->isTool()){
			if($item instanceof WoodenAxe or $item instanceof WoodenHoe or $item instanceof WoodenPickaxe or $item instanceof WoodenShovel or $item instanceof WoodenSword) return 15;
			if($item instanceof StoneAxe or $item instanceof StoneHoe or $item instanceof StonePickaxe or $item instanceof StoneShovel or $item instanceof StoneSword) return 5;
			if($item instanceof DiamondAxe or $item instanceof DiamondHoe or $item instanceof DiamondPickaxe or $item instanceof DiamondShovel or $item instanceof DiamondSword) return 10;
			if($item instanceof IronAxe or $item instanceof IronHoe or $item instanceof IronPickaxe or $item instanceof IronShovel or $item instanceof IronSword) return 14;
			if($item instanceof GoldAxe or $item instanceof GoldHoe or $item instanceof GoldPickaxe or $item instanceof GoldShovel or $item instanceof GoldSword) return 22;
		}

		return 0;
	}

	public static function getEnchantWeight(int $enchantmentId){
		switch($enchantmentId){
			case self::PROTECTION:
				return 10;
			case self::FIRE_PROTECTION:
				return 5;
			case self::FEATHER_FALLING:
				return 2;
			case self::BLAST_PROTECTION:
				return 5;
			case self::RESPIRATION:
				return 2;
			case self::AQUA_AFFINITY:
				return 2;
			case self::SHARPNESS:
				return 10;
			case self::SMITE:
				return 5;
			case self::BANE_OF_ARTHROPODS:
				return 5;
			case self::KNOCKBACK:
				return 5;
			case self::FIRE_ASPECT:
				return 2;
			case self::LOOTING:
				return 2;
			case self::EFFICIENCY:
				return 10;
			case self::SILK_TOUCH:
				return 1;
			case self::UNBREAKING:
				return 5;
			case self::FORTUNE:
				return 2;
			case self::POWER:
				return 10;
			case self::PUNCH:
				return 2;
			case self::FLAME:
				return 2;
			case self::INFINITY:
				return 1;
		}

		return 0;
	}

	public static function getEnchantMaxLevel(int $enchantmentId){
		switch($enchantmentId){
			case self::PROTECTION:
			case self::FIRE_PROTECTION:
			case self::FEATHER_FALLING:
			case self::BLAST_PROTECTION:
			case self::PROJECTILE_PROTECTION:
				return 4;
			case self::THORNS:
				return 3;
			case self::RESPIRATION:
			case self::DEPTH_STRIDER:
				return 3;
			case self::AQUA_AFFINITY:
				return 1;
			case self::SHARPNESS:
			case self::SMITE:
			case self::BANE_OF_ARTHROPODS:
				return 5;
			case self::KNOCKBACK:
			case self::FIRE_ASPECT:
				return 2;
			case self::LOOTING:
				return 3;
			case self::EFFICIENCY:
				return 5;
			case self::SILK_TOUCH:
				return 1;
			case self::UNBREAKING:
			case self::FORTUNE:
				return 3;
			case self::POWER:
				return 5;
			case self::PUNCH:
				return 2;
			case self::FLAME:
			case self::INFINITY:
				return 1;
			case self::LUCK_OF_THE_SEA:
			case self::LURE:
				return 3;
		}

		return 999;
	}

	private $id;
	private $level = 1;
	private $name;
	private $rarity;
	private $activationType;
	private $slot;

	private function __construct(int $id, string $name, int $rarity, int $activationType, int $slot){
		$this->id = $id;
		$this->name = $name;
		$this->rarity = $rarity;
		$this->activationType = $activationType;
		$this->slot = $slot;
	}

	public function getId(): int{
		return $this->id;
	}

	public function getName(): string{
		return $this->name;
	}

	public function getRarity(): int{
		return $this->rarity;
	}

	public function getActivationType(): int{
		return $this->activationType;
	}

	public function getSlot(): int{
		return $this->slot;
	}

	public function hasSlot($slot): bool{
		return ($this->slot & $slot) > 0;
	}

	public function getLevel(): int{
		return $this->level;
	}

	public function setLevel(int $level){
		$this->level = $level;

		return $this;
	}

	public function equals(Enchantment $ent){
		if($ent->getId() == $this->getId() and $ent->getLevel() == $this->getLevel() and $ent->getActivationType() == $this->getActivationType() and $ent->getRarity() == $this->getRarity()){
			return true;
		}

		return false;
	}

	public static function getRandomName(){
		$count = mt_rand(3, 6);
		$set = [];
		while(count($set) < $count){
			$set[] = self::$words[mt_rand(0, count(self::$words) - 1)];
		}

		return implode(" ", $set);
	}
}
