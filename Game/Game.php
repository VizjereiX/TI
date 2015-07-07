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
		CLI::write("Witamy w Witcher Fighter v0.1");
		CLI::writeLine();
		
		// Ustawianie obiektów
		$player = new Witcher();
		$enemy = new Troll();

		// Ustawianie statystyk
		CLI::write("Czy chcesz samodzielnie zdefiniować statystyki (t/n)?");

		if ( CLI::readDefinedValues(['t','n']) == 't' ) {
			$this->setCustomStats($player);
			$this->setCustomStats($enemy);
		}
		
		// Faza walki
		$this->fight($player,$enemy);

		CLI::writeLine();
		CLI::write("Game end");
	}

	/**
	 * Ustaw domyślne parametry postaci walczących
	 */
	public function setCustomStats(Creature $creature)
	{
		CLI::writeLine();
		CLI::write("Ustawianie statystyk dla: ".$creature);
	
		CLI::write(sprintf("Podaj siłę (od 1 do 20) [domyślnie: %d]", $creature->getStrength()));
		$creature->setStrength(CLI::readInteger(1, 20, $creature->getStrength()));
		
		CLI::write(sprintf("Podaj zręczność (od 1 do 20) [domyślnie: %d]", $creature->getDexterity()));
		$creature->setDexterity(CLI::readInteger(1, 20, $creature->getDexterity()));
		
		CLI::write(sprintf("Podaj witalność (od 1 do 20) [domyślnie: %d]", $creature->getVitality()));
		$creature->setVitality(CLI::readInteger(1, 20, $creature->getVitality()));
		
		CLI::write(sprintf("Podaj szybkość (od 1 do 20) [domyślnie: %d]", $creature->getSpeed()));
		$creature->setSpeed(CLI::readInteger(1, 20, $creature->getSpeed()));
	}

	/**
	 * Walka pomiędzy wskazanymi postaciami
	 * @param \Game\Person $player
	 * @param \Game\Enemy $enemy
	 */
	public function fight( Person $player, Enemy $enemy  )
	{
		CLI::writeLine();
		CLI::write(sprintf("Proszę Państwa, walka się rozpoczęła pomiędzy graczem typu %s a przeciwnikiem typu %s",$player,$enemy));
	}
}
