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
	 * @param type $min (opcjonalnie) wartość minimalna
	 * @param type $max (opcjonalnie) wartość maksymalna
	 * @param type $def (opcjonalnie) wartość domyślna
	 * @return type
	 */
	public static function readInteger($min = null, $max = null, $def = null )
	{
		do	{
				$number = filter_var(self::read(),FILTER_SANITIZE_NUMBER_INT);
				
				if ( $def !== null && trim($number) == "" ) {
					return $def;
				}
			
				if ( !is_numeric($number) ) {
					self::write("Podaj prawidlowa liczbe całkowita");
					continue;
				}
				$number = intval($number);
				
				if ( $min !== null && $number < $min ) {
					self::write("Podaj liczbe wieksza lub rowna ".$min);
					continue;
				}
				
				if ( $max !== null && $number > $max ) {
					self::write("Podaj liczbe mniejsza lub rowna ".$max);
					continue;
				}
				return intval($number);
	
		} while ( true );
	}

	/**
	 * Odczytaj z konsoli jeden ze zdefiniowanych znaków tabeli jako parametr
	 * @param array $values
	 * @param mixed $def (opcjonalnie
	 * @return array
	 */
	public static function readDefinedValues(Array $values, $def = null)
	{
		do {
			$value = filter_var(self::read(),FILTER_SANITIZE_URL);
			
			if ( $def !== null && trim($value) == '' ) {
				return $def;
			}
			
			if ( !in_array($value,$values)) {
				self::write("Podaj jedna z wymienionych wartosci: ".implode("/", $values).':');
				continue;
			}
			return $value;
			
		} while ( true );
	}
	
	/**
	 * Zapisz linię
	 */
	public static function writeLine()
	{
		self::write("--------------------------------------------------------");
	}
}