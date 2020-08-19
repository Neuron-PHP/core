<?php

use Neuron\Data\Validation\NumericRange;

class NumericRangeTest extends PHPUnit\Framework\TestCase
{
	public function testRangePass()
	{
		$Validator = new \Neuron\Data\Validation\NumericRange();

		$this->assertTrue(
			$Validator->isValid(
				new \Neuron\Data\Object\NumericRange( 1, 10 )
			)
		);
	}

	public function testRangeFail()
	{
		$Validator = new \Neuron\Data\Validation\NumericRange();

		$this->assertFalse(
			$Validator->isValid(
				new \Neuron\Data\Object\NumericRange( 10, 1 )
			)
		);
	}

}
