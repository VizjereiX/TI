<?php

use Game\Game;

// Autoloadowanie klas
spl_autoload_register(function ($class) {
	$path = str_replace('\\', DIRECTORY_SEPARATOR, $class);
	require $path.'.php';
});

// Włączenie gry
$game = new Game();
$game->start();