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
		CLI::write("Czy chcesz samodzielnie zdefiniowac statystyki (t/n) [domyslnie: n]?");

		if ( CLI::readDefinedValues(['t','n'],'n') == 't' ) {
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
	
		CLI::write(sprintf("Podaj sile (od 1 do 20) [domyslnie: %d]", $creature->getStrength()));
		$creature->setStrength(CLI::readInteger(1, 20, $creature->getStrength()));
		
		CLI::write(sprintf("Podaj zrecznosc (od 1 do 20) [domyslnie: %d]", $creature->getDexterity()));
		$creature->setDexterity(CLI::readInteger(1, 20, $creature->getDexterity()));
		
		CLI::write(sprintf("Podaj witalnosc (od 1 do 20) [domyslnie: %d]", $creature->getVitality()));
		$creature->setVitality(CLI::readInteger(1, 20, $creature->getVitality()));
		
		CLI::write(sprintf("Podaj szybkosc (od 1 do 20) [domyslnie: %d]", $creature->getSpeed()));
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
		CLI::write(sprintf("Start walki graczem typu %s a przeciwnikiem typu %s",$player,$enemy));
		
		$runda = 0;
		
		while ( true )
		{
			// Kolejna runda
			CLI::writeLine();
			CLI::write(sprintf("Runda walki: ".(++$runda)));
			CLI::write("Twoje ({$player}) statystyki: ".$player->getStats());
			CLI::write("Przeciwnika ({$enemy}) statystyki: ".$enemy->getStats());
			
			// Pobierz kolejność walczących
			$fighters = $this->getFightersOrder($player,$enemy);

			// Jeśli wszyscy walczący nie wykonali ruchu...
			while ( count($fighters) > 0 )
			{
				// Zdefiniuj kto jest atakującym a kto obrońcą w tym momencie i wylicz punkty akcji
				$attacker = array_shift($fighters);
				$defender = $attacker === $player ? $enemy : $player;
				$ap = $this->getActionPoints($attacker, $defender);

				// Wypisz kto zaczyna ture
				CLI::write("Swoja ture zaczyna: {$attacker} z {$ap} punktami akcji");

				
				while ( $ap > 0 )
				{
					$a = $this->chooseAction($attacker);
					
					switch ( $a ) {
						case 1:
						case 2:
						case 3:
						case 4:
							
						// Wybrano koniec tury
						case 5:
							$ap = 0;
							break;
					}
					
				}
				
				CLI::read();
			}
			while ( $ap > 0 )
			{
				
				switch ( $a )
				{
					case 1:
						$ap--;
						
						$sk = $this->calculateAttack($player,$enemy);
						$ski = 100 - $sk;
						CLI::write("Probujesz zaatakowac przeciwnika z szansą {$ski}%");
						
						if ( rand(1, 100) >= $sk ) {
							$enemy->setVitality($enemy->getVitality()-1);
							CLI::write("Atak celny, zadajesz przeciwnikowi cios");
							CLI::write("Przeciwnika ({$enemy}) statystyki: ".$enemy->getStats());
						} else {
							CLI::write("Pudlo, atak nieudany!");
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
	 * @return Integer
	 */
	private function calculateAttack(Creature $attacker,Creature $defender)
	{
		$sk = ( ( ($attacker->getDexterity() - $defender->getDexterity() ) / $defender->getDexterity() ) * 100 );
		return max( min( $sk, 90 ), 10 );
	}
	
	/**
	 * Stwórz tablicę kolejności walczących oraz ich punktów akcji
	 * @param \Game\Creature $first
	 * @param \Game\Creature $second
	 * @return Integer 
	 */
	private function getFightersOrder(Creature $first, Creature $second)
	{
		$ap = function ( $attacker, $defender ) {
			return max(floor( $attacker->getSpeed() / $defender->getSpeed() ),1);
		};

		$fighters = [];
		if ( $first->getSpeed() >= $second->getSpeed() ) {
			$fighters[] = $first;
			$fighters[] = $second;
		} else {
			$fighters[] = $second;
			$fighters[] = $first;
		}
		return $fighters;
	}

	/**
	 * Wylicz liczbę punktów akcji pomiędzy atakującym a obrońcą
	 * @param type $first
	 * @param type $second
	 * @return Integer
	 */
	private function getActionPoints ( $first, $second ) {
		return max(floor( $first->getSpeed() / $second->getSpeed() ),1);
	}

	/**
	 * Wybierz akcję w zależności od typu atakującego
	 * @param \Game\Creature $attacker
	 * @return int
	 */
	private function chooseAction(Creature $attacker)
	{
		CLI::write("",true);
		
		if ( $attacker instanceof Person ) {
			CLI::write("Wybierz akcje:");
			CLI::write("[1] Atak - 1 ap");
			CLI::write("[2] Stworzenie eliksiru - 1+ ap");
			CLI::write("[3] Wypicie eliksiru - 1 ap");
			CLI::write("[4] Obrona - 2+ ap");
			CLI::write("[5] Koniec tury - 1+ ap");

			return  CLI::readDefinedValues([1,2,3,4,5]);
		} else {
			CLI::write("{$attacker} wybiera akcje Atak!");
			return 1;
		}
	}
}