<?php

namespace Neuron\Data;

class UnitConversion
{
	const MILLILITERS_PER_OUNCE = 0.03381413;
	const POUNDS_PER_KILOGRAM   = 2.204623;

	/**
	 * @param float $Milliliters
	 * @return float|int
	 */
	public static function millilitersToUsFlOunces( float $Milliliters ) : float
	{
		return $Milliliters * self::MILLILITERS_PER_OUNCE;
	}

	/**
	 * @param float $Ounces
	 * @return float|int
	 */
	public static function usFlOuncesToMilliliters( float $Ounces ) : float
	{
		return $Ounces / self::MILLILITERS_PER_OUNCE;
	}

	/**
	 * @param float $Kilograms
	 * @return float
	 */
	public static function kilogramsToPounds( float $Kilograms ) : float
	{
		return $Kilograms * self::POUNDS_PER_KILOGRAM;
	}

	/**
	 * @param float $Pounds
	 * @return float
	 */
	public static function poundsToKilograms( float $Pounds ) : float
	{
		return $Pounds / self::POUNDS_PER_KILOGRAM;
	}
}
