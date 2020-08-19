<?php

namespace Data;

use Neuron\Data\UnitConversion;
use PHPUnit\Framework\TestCase;

class UnitConversionTest extends TestCase
{
	public function testOuncesToMilliliters()
	{
		$this->assertEquals(
			round( UnitConversion::usFlOuncesToMilliliters( 1 ), 5 ),
			29.57344
		);
	}

	public function testPoundsToKilograms()
	{
		$this->assertEquals(
			round( UnitConversion::poundsToKilograms( 1 ), 5 ),
			0.45359
		);
	}

	public function testKilogramsToPounds()
	{
		$this->assertEquals(
			round( UnitConversion::kilogramsToPounds( 2 ), 5 ),
			4.40925
		);
	}

	public function testMillilitersToOz()
	{
		$this->assertEquals(
			round( UnitConversion::millilitersToUsFlOunces( 100 ), 5 ),
			3.38141
		);
	}
}
