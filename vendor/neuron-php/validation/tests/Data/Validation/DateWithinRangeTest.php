<?php

/**
 * Created by PhpStorm.
 * User: lee
 * Date: 7/27/16
 * Time: 6:55 PM
 */
class DateWithinRangeTest extends PHPUnit\Framework\TestCase
{

	public function testWithinRangeFail()
	{
		$Validator = new \Neuron\Data\Validation\DateWithinRange();

		$Validator->setFormat( 'Y-m-d' );
		$Validator->setRange( new \Neuron\Data\Object\DateRange( '2000-01-01', '2000-01-02') );

		$this->assertFalse( $Validator->isValid( '2015-01-01' ) );
	}

	public function testWithinRangePass()
	{
		$Validator = new \Neuron\Data\Validation\DateWithinRange();

		$Validator->setFormat( 'Y-m-d' );
		$Validator->setRange( new \Neuron\Data\Object\DateRange( '2000-01-01', '2020-01-02' ) );

		$this->assertTrue( $Validator->isValid( '2015-01-01' ) );
	}

}
