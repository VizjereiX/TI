<?php

// Autoloadowanie klas
spl_autoload_register(function ($class) {
	require './classes/' . strtolower($class) . '.php';
});

// Włączenie gry
$game = new Game();
$game->start();