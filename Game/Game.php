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
		CLI::write("Gra uruchomiona!");
		CLI::write("----------------");
		CLI::write("Witamy w Witcher Fighter v0.1");
		CLI::write("Czy chcesz samodzielnie zdefiniować statystyki (t/n)?");
		
		CLI::readDefinedValues(['t','n']);
		$troll = new Troll();
		CLI::write($troll->getStrength());
		
		CLI::write("Game end");
	}

	public function setCustomStats()
	{
		
		
	}
}
