<?php

namespace Game;

/**
 * Istota żyjąca
 */
abstract class Creature
{
	protected $strength		= 1;
	protected $dexterity	= 1;
	protected $vitality		= 1;
	protected $speed		= 1;
	protected $type			= "Istota";

	/**
	 * Pobierz siłę
	 * @return type
	 */
	public function getStrength()
	{
		return $this->strength;
	}
	
	/**
	 * Ustaw siłę
	 * @param type $strength
	 */
	public function setStrength($strength)
	{
		$this->strength = intval($strength);
	}

	/**
	 * Pobierz zręczność
	 * @return type
	 */
	public function getDexterity()
	{
		return $this->dexterity;
	}

	/**
	 * Ustaw zręczność
	 * @param type $dexterity
	 */
	public function setDexterity($dexterity)
	{
		$this->dexterity = intval($dexterity);
	}

	/**
	 * Pobierz witalność
	 * @return type
	 */
	public function getVitality()
	{
		return $this->vitality;
	}
	
	/**
	 * Ustaw witalność
	 * @param type $vitality
	 */
	public function setVitality($vitality)
	{
		$this->vitality = intval($vitality);
	}

	/**
	 * Pobierz szybkość
	 * @return type
	 */
	public function getSpeed()
	{
		return $this->speed;
	}

	/**
	 * Ustaw szybkość
	 * @param type $speed
	 */
	public function setSpeed($speed)
	{
		$this->speed = intval($speed);
	}
	
	/**
	 * Pobierz typ istoty
	 * @return type
	 */
	public function getType()
	{
		return $this->type;
	}
	
	public function __toString()
	{
		return $this->type;
	}
	
	public function getStats()
	{
		return "S:{$this->strength}; Z:{$this->dexterity}; W:{$this->vitality}; S:{$this->speed}";
	}
}