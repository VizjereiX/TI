<?php

namespace Game;

/**
 * Wiedźmin
 */
class Witcher extends Person
{
	protected $type	= "Wiedźmin";

	/**
	 * Konstruktor
	 */
	public function __construct()
	{
		$this->setStrength(6);
		$this->setDexterity(6);
		$this->setVitality(13);
		$this->setSpeed(10);
	}
}
