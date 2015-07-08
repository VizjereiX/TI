<?php

namespace Game;

/**
 * Troll
 */
class Troll extends Enemy
{
	protected $type	= "Troll";

	/**
	 * Konstruktor
	 */
	public function __construct()
	{
		$this->setStrength(7);
		$this->setDexterity(5);
		$this->setVitality(11);
		$this->setSpeed(5);
	}
}
