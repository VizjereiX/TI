<?php

class CLI
{
	const BUFFER = 80;

	/**
	 * Zapisz dane do konsoli
	 * @param mixed $text		Treść
	 * @param bool $newline		Czy zakończyć treść nową linią
	 */
	public static function write($text = "", $newline = true)
	{
		echo $text.( $newline ? "\n" : "");
	}

	/**
	 * Odczytaj dane z wejścia konsoli
	 * @param number $buffer Wielkość bufora odczytu
	 * @return mixed
	 */
	public static function read($buffer = self::BUFFER)
	{
		return trim(fgets(STDIN), $buffer);
	}

	/**
	 * Odczytaj liczbę z wejścia konsoli
	 * @return number
	 */
	public static function readNumber()
	{

		do {
			$number = filter_var(self::read(),FILTER_SANITIZE_NUMBER_INT); 
		} while ( !is_numeric($number) );
		return intval($number);
	}
}

