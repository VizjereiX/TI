<?php

namespace Game;

/**
 * Wiedźmin
 */
class Witcher extends Person
{
	protected $type	= "Wiedzmin";

	/**
	 * Konstruktor
	 */
	public function __construct()
	{
		$this->setStrength(6);
		$this->setDexterity(6);
		$this->setVitality(6);
		$this->setSpeed(10);
	}
}
