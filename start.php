<?php

use Game\Game;

// Autoloadowanie klas
spl_autoload_register(function ($class) {
	$path = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
	if (!file_exists($path)) {
		var_dump($class, $path); die;
	}
	require $path;
});

// Włączenie gry
$game = new Game();
$game->start();
