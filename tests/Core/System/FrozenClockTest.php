<?php

namespace Tests\Core\System;

use Neuron\Core\System\IClock;
use Neuron\Core\System\FrozenClock;
use PHPUnit\Framework\TestCase;

/**
 * Tests for FrozenClock implementation
 */
class FrozenClockTest extends TestCase
{
	public function testImplementsInterface(): void
	{
		$clock = new FrozenClock();
		$this->assertInstanceOf( IClock::class, $clock );
	}

	public function testConstructorWithoutArgumentsUsesCurrentTime(): void
	{
		$before = time();
		$clock = new FrozenClock();
		$after = time();

		$time = $clock->time();

		$this->assertGreaterThanOrEqual( $before, $time );
		$this->assertLessThanOrEqual( $after, $time );
	}

	public function testConstructorFreezesAtSpecificTime(): void
	{
		$timestamp = 1609459200; // 2021-01-01 00:00:00 UTC
		$clock = new FrozenClock( $timestamp );

		$this->assertEquals( $timestamp, $clock->time() );
	}

	public function testTimeStaysFrozen(): void
	{
		$timestamp = 1000000;
		$clock = new FrozenClock( $timestamp );

		$this->assertEquals( $timestamp, $clock->time() );
		usleep( 10000 ); // Wait 10ms
		$this->assertEquals( $timestamp, $clock->time() ); // Still frozen
	}

	public function testSetTimeChangesTime(): void
	{
		$clock = new FrozenClock( 1000000 );

		$clock->setTime( 2000000 );

		$this->assertEquals( 2000000, $clock->time() );
	}

	public function testAdvanceIncreasesTime(): void
	{
		$clock = new FrozenClock( 1000000 );

		$clock->advance( 60 );

		$this->assertEquals( 1000060, $clock->time() );
	}

	public function testAdvanceMultipleTimes(): void
	{
		$clock = new FrozenClock( 1000000 );

		$clock->advance( 30 );
		$clock->advance( 45 );

		$this->assertEquals( 1000075, $clock->time() );
	}

	public function testAdvanceMicrosecondsIncreasesTime(): void
	{
		$clock = new FrozenClock( 1000000, 1000000.0 );

		$clock->advanceMicroseconds( 500000 ); // 0.5 seconds

		$this->assertEquals( 1000000, $clock->time() );
		$this->assertEquals( 1000000.5, $clock->microtime( true ) );
	}

	public function testMicrotimeAsFloat(): void
	{
		$microtime = 1000000.123456;
		$clock = new FrozenClock( 1000000, $microtime );

		$result = $clock->microtime( true );

		$this->assertIsFloat( $result );
		$this->assertEquals( $microtime, $result );
	}

	public function testMicrotimeAsString(): void
	{
		$clock = new FrozenClock( 1000000, 1000000.5 );

		$result = $clock->microtime( false );

		$this->assertIsString( $result );
		$this->assertMatchesRegularExpression( '/^0\.\d+ \d+$/', $result );
		$this->assertStringContainsString( '1000000', $result );
	}

	public function testDateFormatsTimestamp(): void
	{
		$timestamp = 1609459200; // 2021-01-01 00:00:00 UTC
		$clock = new FrozenClock( $timestamp );

		$date = $clock->date( 'Y-m-d H:i:s', null );

		$this->assertStringContainsString( '2021-01-01', $date );
	}

	public function testDateWithSpecificTimestamp(): void
	{
		$clock = new FrozenClock( 1000000 );

		$date = $clock->date( 'Y-m-d', 1609459200 );

		$this->assertStringContainsString( '2021-01-01', $date );
	}

	public function testNowReturnsDateTimeImmutable(): void
	{
		$timestamp = 1609459200;
		$clock = new FrozenClock( $timestamp );

		$now = $clock->now();

		$this->assertInstanceOf( \DateTimeImmutable::class, $now );
		$this->assertEquals( $timestamp, $now->getTimestamp() );
	}

	public function testSleepAdvancesTime(): void
	{
		$clock = new FrozenClock( 1000000 );

		$clock->sleep( 60 );

		$this->assertEquals( 1000060, $clock->time() );
	}

	public function testSleepDoesNotActuallySleep(): void
	{
		$clock = new FrozenClock( 1000000 );

		$start = microtime( true );
		$clock->sleep( 10 ); // Would normally sleep 10 seconds
		$elapsed = microtime( true ) - $start;

		$this->assertLessThan( 0.1, $elapsed ); // Should be instant
		$this->assertEquals( 1000010, $clock->time() );
	}

	public function testUsleepAdvancesTime(): void
	{
		$clock = new FrozenClock( 1000000, 1000000.0 );

		$clock->usleep( 500000 ); // 0.5 seconds

		$this->assertEquals( 1000000, $clock->time() );
		$this->assertEquals( 1000000.5, $clock->microtime( true ) );
	}

	public function testUsleepDoesNotActuallySleep(): void
	{
		$clock = new FrozenClock( 1000000 );

		$start = microtime( true );
		$clock->usleep( 1000000 ); // Would normally sleep 1 second
		$elapsed = microtime( true ) - $start;

		$this->assertLessThan( 0.1, $elapsed ); // Should be instant
	}

	public function testComplexTimeAdvancement(): void
	{
		$clock = new FrozenClock( 1000000, 1000000.0 );

		// Advance by various increments
		$clock->advance( 60 );              // +60 seconds
		$clock->advanceMicroseconds( 500000 ); // +0.5 seconds
		$clock->sleep( 30 );                // +30 seconds
		$clock->usleep( 250000 );           // +0.25 seconds

		$this->assertEquals( 1000090, $clock->time() );
		$this->assertEquals( 1000090.75, $clock->microtime( true ) );
	}

	public function testSetTimeResetsEverything(): void
	{
		$clock = new FrozenClock( 1000000, 1000000.5 );

		$clock->setTime( 2000000, 2000000.0 );

		$this->assertEquals( 2000000, $clock->time() );
		$this->assertEquals( 2000000.0, $clock->microtime( true ) );
	}

	public function testSetTimeWithNullMicrotimeUsesTimestamp(): void
	{
		$clock = new FrozenClock( 1000000 );

		$clock->setTime( 2000000, null );

		$this->assertEquals( 2000000, $clock->time() );
		$this->assertEquals( 2000000.0, $clock->microtime( true ) );
	}
}
