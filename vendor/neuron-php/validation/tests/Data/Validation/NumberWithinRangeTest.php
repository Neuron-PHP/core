<?php

use Neuron\Data\Validation\NumericRange;

class NumberWithinRangeTest extends PHPUnit\Framework\TestCase
{
	public function testRangePass()
	{
		$Validator = new \Neuron\Data\Validation\NumberWithinRange();

		$Validator->setRange(
			new \Neuron\Data\Object\NumericRange( 1, 10 )
		);

		$this->assertTrue(
			$Validator->isValid( 5 )
		);
	}

	public function testRangeFail()
	{
		$Validator = new \Neuron\Data\Validation\NumberWithinRange();

		$Validator->setRange(
			new \Neuron\Data\Object\NumericRange( 1, 10 )
		);

		$this->assertFalse(
			$Validator->isValid( 11 )
		);
	}
}
