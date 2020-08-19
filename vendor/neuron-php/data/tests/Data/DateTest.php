<?php

use Neuron\Data\Date;

class DateUtilTest extends PHPUnit\Framework\TestCase
{
	protected function setUp()
	{
	}

	protected function tearDown()
	{
	}

	public function testDateOnly()
	{
		$this->assertEquals(
			Neuron\Data\Date::only( '2018-01-15 01:02:03' ),
			'2018-01-15'
		);
	}

	public function testToday()
	{
		$this->assertEquals(
			Neuron\Data\Date::today(),
			date( 'Y-m-d' )
		);
	}

	public function testTomorrow()
	{
		$this->assertEquals(
			Neuron\Data\Date::tomorrow(),
			date( 'Y-m-d', strtotime( 'tomorrow' ) )
		);
	}

	public function testYesterday()
	{
		$this->assertEquals(
			Neuron\Data\Date::yesterday(),
			date( 'Y-m-d', strtotime( 'yesterday' ) )
		);
	}

	public function testDaysAsText()
	{
		$this->assertEquals(
			Neuron\Data\Date::daysAsText( 1 ),
			'1 day'
		);

		$this->assertEquals(
			Neuron\Data\Date::daysAsText( 2 ),
			'2 days'
		);

		$this->assertEquals(
			Neuron\Data\Date::daysAsText( 7 ),
			'1 week'
		);

		$this->assertEquals(
			Neuron\Data\Date::daysAsText( 14 ),
			'2 weeks'
		);

		$this->assertEquals(
			Neuron\Data\Date::daysAsText( 15 ),
			'2 weeks, 1 day'
		);

		$this->assertEquals(
			Neuron\Data\Date::daysAsText( 16 ),
			'2 weeks, 2 days'
		);

		$this->assertEquals(
			Neuron\Data\Date::daysAsText( 30 ),
			'1 month'
		);

		$this->assertEquals(
			Neuron\Data\Date::daysAsText( 60 ),
			'2 months'
		);

		$this->assertEquals(
			Neuron\Data\Date::daysAsText( 61 ),
			'2 months, 1 day'
		);

		$this->assertEquals(
			Neuron\Data\Date::daysAsText( 62 ),
			'2 months, 2 days'
		);

		$this->assertEquals(
			Neuron\Data\Date::daysAsText( 68 ),
			'2 months, 1 week, 1 day'
		);

		$this->assertEquals(
			Neuron\Data\Date::daysAsText( 365 ),
			'1 year'
		);

		$this->assertEquals(
			Neuron\Data\Date::daysAsText( 365 + 60 + 14 + 2 ),
			'1 year, 2 months, 2 weeks, 2 days'
		);

	}

	public function testDifferenceUnitAsText()
	{
		// year

		$this->assertEquals(
			Neuron\Data\Date::differenceUnitAsText( 31536000, 31536000 * 2 ),
			'1 year'
		);

		// years

		$this->assertEquals(
			Neuron\Data\Date::differenceUnitAsText( 31536000, 31536000 * 3 ),
			'2 years'
		);

		// month

		$this->assertEquals(
			Neuron\Data\Date::differenceUnitAsText( 2592000, 2592000 * 2 ),
			'1 month'
		);

		// week

		$this->assertEquals(
			Neuron\Data\Date::differenceUnitAsText( 604800, 604800 * 2 ),
			'1 week'
		);

		// day

		$this->assertEquals(
			Neuron\Data\Date::differenceUnitAsText( 86400, 86400 * 2 ),
			'1 day'
		);

		// hour

		$this->assertEquals(
			Neuron\Data\Date::differenceUnitAsText( 3600, 3600 * 2 ),
			'1 hour'
		);

		// minute

		$this->assertEquals(
			Neuron\Data\Date::differenceUnitAsText( 60, 60 * 2 ),
			'1 minute'
		);

		// second

		$this->assertEquals(
			Neuron\Data\Date::differenceUnitAsText( 1, 1 * 2 ),
			'1 second'
		);

	}

	public function testLeapYear()
	{
		$this->assertTrue(
			Neuron\Data\Date::isLeapYear( 2004 )
		);

		$this->assertFalse(
			Neuron\Data\Date::isLeapYear( 2003 )
		);
	}

	public function testDaysInMonth()
	{
		$this->assertTrue(
			Neuron\Data\Date::getDaysInMonth( 1 ) == 31
		);

		$this->assertTrue(
			Neuron\Data\Date::getDaysInMonth( 2, 2004 ) == 29
		);

		$this->assertTrue(
			Neuron\Data\Date::getDaysInMonth( 2, 1805 ) == 28
		);
	}

	public function testDiff()
	{
		$this->assertTrue(
			Neuron\Data\Date::diff( date( 'Y-m-d' ), date( 'Y-m-d', strtotime( "-30 days" ) ) ) == 30
		);
	}

	public function testSubtractDays()
	{
		$this->assertTrue(
			Neuron\Data\Date::subtractDays( 8, '2015-01-30' ) == '2015-01-22'
		);

		$this->assertFalse(
			Neuron\Data\Date::subtractDays( 8, '2015-01-30' ) == '2015-01-21'
		);
	}

	public function testDiffDateTime()
	{
		$this->assertEquals(
			0,
			\Neuron\Data\Date::compare(
				'2020-03-27 12:00:00',
				'2020-03-27 12:00:00'
			)
		);

		$this->assertEquals(
			-1,
			\Neuron\Data\Date::compare(
				'2020-03-27 11:00:00',
				'2020-03-27 12:00:00'
			)
		);

		$this->assertEquals(
			1,
			\Neuron\Data\Date::compare(
				'2020-03-27 13:00:00',
				'2020-03-27 12:00:00'
			)
		);
	}

	public function testGetDay()
	{
		$this->assertEquals(
			Date::getDay( '2019-06-28' ),
			'Friday'
		);
	}

	public function testIsWeekend()
	{
		$this->assertTrue(
			Date::isWeekend( '2019-06-29' )
		);

		$this->assertTrue(
			Date::isWeekend( '2019-06-30' )
		);

		$this->assertFalse(
			Date::isWeekend( '2019-06-28' )
		);
	}

	public function testWorkingDays()
	{
		$this->assertEquals(
			Date::getWorkingDays(
				new \Neuron\Data\Object\DateRange( '2019-06-01', '2019-06-30' )
			),
			20
		);
	}
}
