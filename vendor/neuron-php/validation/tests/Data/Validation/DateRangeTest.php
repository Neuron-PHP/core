<?php

class DateRangeTest extends PHPUnit\Framework\TestCase
{
	public function testRangeFail()
	{
		$Validator = new \Neuron\Data\Validation\DateRange();

		$Validator->setFormat( 'Y-m-d' );

		$this->assertFalse(
			$Validator->isValid(
				new \Neuron\Data\Object\DateRange( '2000-01-01', '2000-01-01' )
			)
		);
	}

	public function testRangePass()
	{
		$Validator = new \Neuron\Data\Validation\DateRange();

		$Validator->setFormat( 'Y-m-d' );

		$this->assertTrue(
			$Validator->isValid(
				new \Neuron\Data\Object\DateRange( '2000-01-01', '2000-01-03' )
			)
		);
	}

}
