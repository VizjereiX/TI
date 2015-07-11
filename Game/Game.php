<?php

namespace Game;

use \Game\Helpers\CLI;

/**
 * Gra
 */
class Game
{
	// Akcje atakującego
	const ACTION_ATTACK			= 1;
	const ACTION_MAKE_ELIXIR	= 2;
	const ACTION_DRINK_ELIXIR	= 3;
	const ACTION_DEFEND			= 4;
	CONST ACTION_FINISH			= 5;

	/**
	 * Uruchom grę
	 */
	public function start()
	{
		// Test konsoli
		CLI::write("Gra uruchomiona!");
		CLI::write("Witamy w Witcher Fighter v0.1");
		CLI::writeLine();
	
		// Pętla gry
		while ( true )
		{
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
			
			// Powtorzenie gry?
			CLI::write("Czy chcesz powtorzyc gre (t/n) [domyslnie: n]?");

			if ( CLI::readDefinedValues(['t','n'],'n') == 'n' ) {
				break;
			}
		}

		CLI::writeLine();
		CLI::write("Game end");
	}

	/**
	 * Ustaw statystyki dla wskazanej istoty
	 * @param \Game\Creature $creature
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
			
			// Zresetuj obrone gracza w tej rundzie
			$player->setDefend(false);

			// Jeśli wszyscy walczący nie wykonali ruchu...
			while ( count($fighters) > 0 )
			{
				// Zdefiniuj kto jest atakującym a kto obrońcą w tym momencie i wylicz punkty akcji
				$attacker = array_shift($fighters);
				$defender = $attacker === $player ? $enemy : $player;
				$ap = $this->getActionPoints($attacker, $defender);

				// Wypisz kto zaczyna ture
				CLI::write("Swoja ture zaczyna: {$attacker} z {$ap} punktami akcji");

				// Jesli atakujacy ma wciaz punkty akcji
				while ( $ap > 0 )
				{
					$a = $this->chooseAction($attacker, $ap);
					
					switch ( $a )
					{
						// Wybrano atak
						case 1:
							$ap--;
							$this->makeAttack($attacker,$defender);
							
							if ( $defender->getVitality() == 0 ) {
								$ap = 0;
							}
							break;

						case 2:
							break;
						
						case 3:
							break;
						
						// Wybrano obrone
						case 4:
							$ap -= 2;
							$this->makeDefend($attacker);
							break;
							
						// Wybrano koniec tury
						case 5:
							$ap = 0;
							break;
					}
					
				}
							
				// Broniacy sie zostal zabity?
				if ( $defender->getVitality() == 0 ) {
					CLI::writeLine();
					CLI::write("{$defender} zostal zabity przez {$attacker}! Koniec walki.");
					CLI::writeLine();
					return;
				}

				// Koniec tury atakujacego
				CLI::write("{$attacker} konczy swoja ture!");
				CLI::read();
			}

			// Koniec rundy
			CLI::write("Koniec rundy");
		}
	}

	
	/**
	 * Wykonaj atak
	 * @param \Game\Creature $attacker
	 * @param \Game\Creature $defender
	 */
	private function makeAttack(Creature $attacker,Creature $defender)
	{
		CLI::write("{$attacker} atakuje cel: {$defender}");
		
		// Wylicz skutecznosc ataku
		$sk = round( \max( \min( ( 50 + ( ($attacker->getDexterity() - $defender->getDexterity() ) / $defender->getDexterity() ) * 100 ), 90 ), 10 ) );
		CLI::write("Oszacowana skutecznosc ataku: {$sk}%");
		
		if ( \rand(1, 100) <= $sk ) {
			$defender->setVitality($defender->getVitality()-1);
			CLI::write("Atak celny {$attacker} zadaje celny cios w {$defender}");
			CLI::write("Aktualna witalnosc przeciwnika: {$defender->getVitality()}");
		} else {
			CLI::write("Pudlo, {$attacker} nie trafia w {$defender}");
		}
	}
	
	/**
	 * Zwróć tablicę kolejności walczących
	 * @param \Game\Creature $first
	 * @param \Game\Creature $second
	 * @return Integer 
	 */
	private function getFightersOrder(Creature $first, Creature $second)
	{
		if ( $first->getSpeed() >= $second->getSpeed() ) {
			return [ $first, $second ];
		} else {
			return [ $second, $first ];
		}
	}

	/**
	 * Wylicz liczbę punktów akcji pomiędzy atakującym a obrońcą
	 * @param type $first
	 * @param type $second
	 * @return Integer
	 */
	private function getActionPoints( $first, $second )
	{
		return max(floor( $first->getSpeed() / $second->getSpeed() ),1);
	}

	/**
	 * Wybierz akcję w zależności od typu atakującego
	 * @param \Game\Creature $attacker
	 * @param integer $ap
	 * @return integer
	 */
	private function chooseAction(Creature $attacker, $ap)
	{
		CLI::write("",true);
		
		// Jesli atakujacy sie to postac gracza
		if ( $attacker instanceof Person ) {
			CLI::write("Wybierz akcje:");
			CLI::write("[1] Atak - 1 ap");
			CLI::write("[2] Stworzenie eliksiru - 1+ ap");
			CLI::write("[3] Wypicie eliksiru - 1 ap");
			CLI::write("[4] Obrona - 2+ ap");
			CLI::write("[5] Koniec tury - 1+ ap");

			$a = null;

			while ( true ) {
				$a = CLI::readDefinedValues([
					self::ACTION_ATTACK,
					self::ACTION_MAKE_ELIXIR,
					self::ACTION_DRINK_ELIXIR,
					self::ACTION_DEFEND,
					self::ACTION_FINISH
				],1);
				
				if ( $ap < 2 && $a == self::ACTION_DEFEND ) {
					CLI::write("{$attacker} nie ma tylu punktow akcji by wykonac ten ruch! Trzeba wybrac ponownie akcje.");
					continue;
				}
				break;
			}

			return $a;
		} else {
			CLI::write("{$attacker} wybiera akcje Atak!");
			return 1;
		}
	}
	
	/**
	 * Postac sie broni
	 * @param \Game\Person $person
	 */
	private function makeDefend(Person $person)
	{
		$person->setDefend(true);
		CLI::write("{$person} sie broni i nie wykonuje innych akcji, ale podwyzsza swoja zrecznosc do {$person->getDexterity()}");
	}
}