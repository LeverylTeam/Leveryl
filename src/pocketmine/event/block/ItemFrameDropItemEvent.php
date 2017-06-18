<?php

/*
 * Ported From Tesseract :)
 * */

namespace pocketmine\event\block;

use pocketmine\block\Block;
use pocketmine\event\Cancellable;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\tile\ItemFrame;

class ItemFrameDropItemEvent extends BlockEvent implements Cancellable{
	
	public static $handlerList = null;

	/** @var  Player */
	private $player;
	
	/** @var  Item */
	private $item;
	
	/** @var  ItemFrame */
	private $itemFrame;
	
	/** @var  Block */
	protected $block;

	public function __construct(Player $player, Block $block, ItemFrame $itemFrame, Item $item){
		$this->player = $player;
		$this->block = $block;
		$this->itemFrame = $itemFrame;
		$this->item = $item;
	}

	public function getPlayer(){
		return $this->player;
	}

	public function getItemFrame(){
		return $this->itemFrame;
	}

	public function getItem(){
		return $this->item;
	}
	
	public function getBlock(){
		return $this->block;
	}

	/**
	 * @return EventName|string
	 */
	public function getName(){
		return "ItemFrameDropItemEvent";
	}

}
