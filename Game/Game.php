<?php

namespace Game;

use \Game\Helpers\CLI;

/**
 * Gra
 */
class Game
{
	/**
	 * Uruchom grę
	 */
	public function start()
	{
		// Test konsoli
		CLI::write("Game start");
		
		
		$troll = new Troll();
		CLI::write($troll->getStrength());
		
		CLI::write("Game end");
		
	}
}
