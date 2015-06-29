<?php

// Autoloadowanie klas
spl_autoload_register(function ($class) {
	var_dump($class);
	require './classes/' . strtolower($class) . '.php';
});

echo "Jak masz na imię?";

$name = \CLI::read();

$z = new CLI();
echo "dzieki".$name;
//echo $name;