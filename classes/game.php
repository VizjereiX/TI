<?php

use \CLI;

class Game
{
	public function start()
	{
		// Test konsoli
		CLI::write("test");
		var_dump(CLI::readNumber());
	}
}
