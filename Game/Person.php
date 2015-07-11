<?php

namespace Game;

/**
 * Klasa dla postaci w grach
 */
abstract class Person extends Creature
{
	protected $defend = false;

	/**
	 * Ustaw obronę
	 * @param bool $bool
	 */
	public function setDefend($bool = false)
	{
		$this->defend = $bool;
	}

	/**
	 * Sprawdź czy się broni
	 * @return bool
	 */
	public function getDefend()
	{
		return $this->defend;
	}

	/**
	 * Pobierz zręczność
	 * @return type
	 */
	public function getDexterity()
	{
		if ( $this->defend == true ) {
			return round( $this->dexterity * 1.5 );
		} else {
			return $this->dexterity;
		}
	}
}
