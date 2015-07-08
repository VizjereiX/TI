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
	private function setCustomStats(Creature $creature)
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
	private function fight( Person $player, Enemy $enemy  )
	{
		CLI::writeLine();
		CLI::write(sprintf("Proszę Państwa, walka się rozpoczęła pomiędzy graczem typu %s a przeciwnikiem typu %s",$player,$enemy));
		
		$runda = 0;
		
		while ( true )
		{
			// Kolejna runda
			CLI::writeLine();
			CLI::write(sprintf("Runda walki: ".(++$runda)));
			
			// Wylicz punkty ruchu
			$ap = max(floor( $player->getSpeed() / $enemy->getSpeed() ),1);
			CLI::write("Twoje ({$player}) statystyki: ".$player->getStats());
			CLI::write("Przeciwnika ({$enemy}) statystyki: ".$enemy->getStats());

			while ( $ap > 0 )
			{
				CLI::writeLine();
				CLI::write("Twoje punkty ruchu: ".$ap);
				CLI::write("Wybierz akcję:");
				CLI::write("[1] Atak - 1 ap");
				CLI::write("[2] Stworzenie eliksiru - 1+ ap");
				CLI::write("[3] Wypicie eliksiru - 1 ap");
				if ( $ap >= 2) {
					CLI::write("[4] Obrona - 2+ ap");
				}
				CLI::write("[5] Koniec tury - 1+ ap");
			
			
				$a =  CLI::readDefinedValues([1,2,3,4,5]);
				CLI::writeLine();
				switch ( $a )
				{
					case 1:
						$ap--;
						
						$sk = $this->calculateAttack($player,$enemy);
						$ski = 100 - $sk;
						CLI::write("Próbujesz zaatakować przeciwnika z szansą {$ski}%");
						
						if ( rand(1, 100) >= $sk ) {
							$enemy->setVitality($enemy->getVitality()-1);
							CLI::write("Atak celny, zadajesz przeciwnikowi cios");
							CLI::write("Przeciwnika ({$enemy}) statystyki: ".$enemy->getStats());
						} else {
							CLI::write("Pudło, atak nieudany!");
						}
						break;
					
					case 5:
						$ap = 0;
						break;
				}
				
				// Przeciwnik zabity
				if ( $enemy->getVitality() == 0 ) {
					break;
				}
			}


			CLI::write("Koniec tury");
		}
	}
	
	/**
	 * Przelicz szansę na skuteczny atak
	 * @param \Game\Creature $attacker
	 * @param \Game\Creature $defender
	 * @return type
	 */
	private function calculateAttack(Creature $attacker,Creature $defender)
	{
		$sk = ( ( ($attacker->getDexterity() - $defender->getDexterity() ) / $defender->getDexterity() ) * 100 );
		return max( min( $sk, 90 ), 10 );
	}
	
}
