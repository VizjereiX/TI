<?php

namespace Game\Helpers;

/**
 * Obsługa konsoli
 */
final class CLI
{
	/**
	 * Rozmiar bufora
	 */
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
	public static function readInteger()
	{
		do	{
				$number = filter_var(self::read(),FILTER_SANITIZE_NUMBER_INT);
			
				if ( !is_numeric($number) ) {
					self::write("Podaj prawidłową liczbę całkowitą");
					continue;
				}
				return intval($number);
	
		} while ( true );
	}

	public static function readDefinedValues(Array $values)
	{
		do {
			$value = filter_var(self::read(),FILTER_SANITIZE_STRING);
			
			if ( !in_array($value,$values)) {
				self::write("Podaj jedną z wymienionych wartości: ".implode("/", $values).':');
				var_dump($value);
				continue;
			}
			return $value;
			
		} while ( true );
	}
}