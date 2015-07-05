<?php

namespace Game;

/**
 * Troll
 */
class Troll extends Enemy
{
	/**
	 * Konstruktor
	 */
	public function __construct()
	{
		$this->setStrength(6);
		$this->setDexterity(7);
		$this->setVitality(8);
		$this->setSpeed(3);
	}
}
