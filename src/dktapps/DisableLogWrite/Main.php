<?php

namespace dktapps\DisableLogWrite;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\MainLogger;

class Main extends PluginBase{

	public function onEnable(){
		//After the logger thread is stopped, it will simply behave as a Threaded
		// object which other Threads can synchronize with for console output.
		$logger = MainLogger::getLogger();
		$logger->shutdown();

		//PROBLEM: Need to join to prevent race conditions trying to delete the log file.
		//This however causes the server to crash on shutdown.
		$logger->join();

		$this->getLogger()->debug("Writing to server.log disabled");

		//Delete anything that got written to the log file before the plugin was loaded
		unlink(\pocketmine\DATA . "server.log") or $this->getLogger()->error("Failed to delete server log");
	}
}