<?php

namespace pocketmine\entity;


use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\level\Level;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\protocol\AddEntityPacket;
use pocketmine\Player;

class EnderPearl extends Projectile {
    const NETWORK_ID = 87;

    public $width = 0.25;
    public $length = 0.25;
    public $height = 0.25;

    protected $gravity = 0.03;
    protected $drag = 0.01;
    protected $player;

    private $hasTeleportedShooter = false;

    public function __construct(Level $level, CompoundTag $nbt, Entity $shootingEntity = null) {
        parent::__construct($level, $nbt, $shootingEntity);
    }

    public function teleportShooter() {
        if (!$this->hasTeleportedShooter) {
            $this->hasTeleportedShooter = true;
            if ($this->isAlive()) {
                if ($this->shootingEntity instanceof Player and $this->y > 0) {
                    $this->shootingEntity->attack(5, new EntityDamageEvent($this->shootingEntity, EntityDamageEvent::CAUSE_FALL, 5));
                    $this->shootingEntity->teleport($this->getPosition());
                }

                $this->kill();
            }
        }
    }

    public function onUpdate($currentTick) {
        if ($this->closed) {
            return false;
        }

        $this->timings->startTiming();

        $hasUpdate = parent::onUpdate($currentTick);

        if ($this->age > 1200 or $this->isCollided) {
            $this->teleportShooter();
            $hasUpdate = true;
        }

        $this->timings->stopTiming();

        return $hasUpdate;
    }


    public function spawnTo(Player $player) {
        $pk = new AddEntityPacket();
        $pk->type = self::NETWORK_ID;
        $pk->eid = $this->getId();
        $pk->x = $this->x;
        $pk->y = $this->y;
        $pk->z = $this->z;
        $pk->speedX = $this->motionX;
        $pk->speedY = $this->motionY;
        $pk->speedZ = $this->motionZ;
        $pk->metadata = $this->dataProperties;
        $player->dataPacket($pk);

        parent::spawnTo($player);
    }
}
