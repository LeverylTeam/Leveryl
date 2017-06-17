<?php
namespace pocketmine\inventory;

use pocketmine\math\Vector3;
use pocketmine\inventory\Inventory;
use pocketmine\inventory\InventoryHolder;

class WindowHolder extends Vector3 implements InventoryHolder{
    public $inventory;

    public function __construct($x, $y, $z, Inventory $inventory){
        parent::__construct($x, $y, $z);
        $this->inventory = $inventory;
    }

    public function getInventory(){
        return $this->inventory;
    }
}