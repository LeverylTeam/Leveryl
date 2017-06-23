<?php

/*
 *     __						    _
 *    / /  _____   _____ _ __ _   _| |
 *   / /  / _ \ \ / / _ \ '__| | | | |
 *  / /__|  __/\ V /  __/ |  | |_| | |
 *  \____/\___| \_/ \___|_|   \__, |_|
 *						      |___/
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author LeverylTeam
 * @link https://github.com/LeverylTeam
 *
 * Based on PocketMine-MPTesterPlugin by PMMP
 * Modified to Also test PlayerConnections
 * PlayerTest is from "Specter" by @falkirks
 *
*/

namespace Leveryl;

use Leveryl\network\SpecterInterface;
use pocketmine\event\Listener;
use pocketmine\event\server\StartupFinishEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

class Main extends PluginBase implements Listener
{

    /** @var Test[] */
    protected $waitingTests = [];
    /** @var Test|null */
    protected $currentTest = null;
    /** @var Test[] */
    protected $completedTests = [];
    /** @var int */
    protected $currentTestNumber = 0;
    /** @var  SpecterInterface */
    private $interface;
    /** @var int */
    protected $pltest;

    public function onEnable()
    {
        $this->interface = new SpecterInterface($this);

        $this->getServer()->getScheduler()->scheduleRepeatingTask(new CheckTestCompletionTask($this), 10);
        $this->waitingTests[] = new tests\AsyncTaskMemoryLeakTest($this);

        $this->getServer()->getNetwork()->registerInterface($this->interface);
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onServerStartup(StartupFinishEvent $ev)
    {
        $this->getLogger()->notice("Running test #" . (++$this->currentTestNumber) . " (PlayerJoin Test)");
        if ($this->getInterface()->openSession("TestPlayer", "CITESTER", 19133)) {
            $this->getLogger()->notice("Finished test #" . $this->currentTestNumber . " (PlayerJoin Test): PASS");
        } else {
            $this->getLogger()->notice("Finished test #" . $this->currentTestNumber . " (PlayerJoin Test): FAIL");
        }
    }

    /**
     * @return Test|null
     */
    public function getCurrentTest()
    {
        return $this->currentTest;
    }

    public function startNextTest(): bool
    {
        $this->currentTest = array_shift($this->waitingTests);
        if ($this->currentTest !== null) {
            $this->getLogger()->notice("Running test #" . (++$this->currentTestNumber) . " (" . $this->currentTest->getName() . ")");
            $this->currentTest->start();
            return true;
        } else {
            return false;
        }
    }

    public function onTestCompleted(Test $test)
    {
        $message = "Finished test #" . $this->currentTestNumber . " (" . $test->getName() . "): ";

        switch ($test->getResult()) {
            case Test::RESULT_OK:
                $message .= "PASS";
                break;
            case Test::RESULT_FAILED:
                $message .= "FAIL";
                break;
            case Test::RESULT_ERROR:
                $message .= "ERROR";
                break;
            case Test::RESULT_WAITING:
                $message .= "TIMEOUT";
                break;
            default:
                $message .= "UNKNOWN";
                break;
        }

        $this->getLogger()->notice($message);

        $this->completedTests[$this->currentTestNumber] = $test;
        $this->currentTest = null;
    }

    public function onAllTestsCompleted()
    {
        $this->getLogger()->notice(TextFormat::YELLOW . "All tests finished, stopping the server");
        $this->getServer()->shutdown();
    }

    /**
     * @return SpecterInterface
     */
    public function getInterface()
    {
        return $this->interface;
    }
}