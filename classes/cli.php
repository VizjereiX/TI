<?php

class CLI
{
	public static function read()
	{
		return trim(fgets(STDIN));
	}
}

