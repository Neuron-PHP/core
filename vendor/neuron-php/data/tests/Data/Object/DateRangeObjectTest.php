<?php

use Neuron\Data\Object\DateRange;
use PHPUnit\Framework\TestCase;

class DateRangeObjectTest extends TestCase
{
	public function testGetLengthInDays()
	{
		$Range = new DateRange(
			date( 'Y-m-d' ),
			date( 'Y-m-d', strtotime( "+7 days" ) )
		);

		$this->assertEquals(
			7,
			$Range->getLengthInDays()
		);

		$this->assertNotEquals(
			8,
			$Range->getLengthInDays()
		);
	}

	public function testGetLengthInDaysAndTime()
	{
		$Range = new DateRange(
			date( 'Y-m-d 07:00:00' ),
			date( 'Y-m-d 08:00:00', strtotime( "+7 days" ) )
		);

		$this->assertEquals(
			7,
			$Range->getLengthInDays()
		);

		$this->assertNotEquals(
			8,
			$Range->getLengthInDays()
		);

	}

	public function testGetLengthInDaysFormat()
	{
		$Range = new DateRange(
			date( 'm/d/Y' ),
			date( 'm/d/Y', strtotime( "+7 days" ) )
		);

		$this->assertEquals(
			7,
			$Range->getLengthInDays()
		);

		$this->assertNotEquals(
			8,
			$Range->getLengthInDays()
		);

	}

}
