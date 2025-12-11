<?php

namespace Tests\Core\System;

use Neuron\Core\System\IClock;
use Neuron\Core\System\RealClock;
use PHPUnit\Framework\TestCase;

/**
 * Tests for RealClock implementation
 */
class RealClockTest extends TestCase
{
	private IClock $clock;

	protected function setUp(): void
	{
		$this->clock = new RealClock();
	}

	public function testImplementsInterface(): void
	{
		$this->assertInstanceOf( IClock::class, $this->clock );
	}

	public function testTimeReturnsInteger(): void
	{
		$time = $this->clock->time();

		$this->assertIsInt( $time );
		$this->assertGreaterThan( 0, $time );
	}

	public function testTimeIsCurrentTime(): void
	{
		$before = time();
		$clockTime = $this->clock->time();
		$after = time();

		$this->assertGreaterThanOrEqual( $before, $clockTime );
		$this->assertLessThanOrEqual( $after, $clockTime );
	}

	public function testMicrotimeAsFloatReturnsFloat(): void
	{
		$microtime = $this->clock->microtime( true );

		$this->assertIsFloat( $microtime );
		$this->assertGreaterThan( 0, $microtime );
	}

	public function testMicrotimeAsStringReturnsString(): void
	{
		$microtime = $this->clock->microtime( false );

		$this->assertIsString( $microtime );
		$this->assertMatchesRegularExpression( '/^0\.\d+ \d+$/', $microtime );
	}

	public function testDateFormatsCurrentTime(): void
	{
		$date = $this->clock->date( 'Y-m-d' );

		$this->assertIsString( $date );
		$this->assertMatchesRegularExpression( '/^\d{4}-\d{2}-\d{2}$/', $date );
	}

	public function testDateFormatsSpecificTimestamp(): void
	{
		$timestamp = 1609459200; // 2021-01-01 00:00:00 UTC
		$date = $this->clock->date( 'Y-m-d H:i:s', $timestamp );

		$this->assertStringContainsString( '2021-01-01', $date );
	}

	public function testDateWithNullUsesCurrentTime(): void
	{
		$expectedDate = date( 'Y-m-d' );
		$clockDate = $this->clock->date( 'Y-m-d', null );

		$this->assertEquals( $expectedDate, $clockDate );
	}

	public function testNowReturnsDateTimeImmutable(): void
	{
		$now = $this->clock->now();

		$this->assertInstanceOf( \DateTimeImmutable::class, $now );
	}

	public function testNowIsCurrentTime(): void
	{
		$before = new \DateTimeImmutable();
		$now = $this->clock->now();
		$after = new \DateTimeImmutable();

		$this->assertGreaterThanOrEqual( $before->getTimestamp(), $now->getTimestamp() );
		$this->assertLessThanOrEqual( $after->getTimestamp(), $now->getTimestamp() );
	}

	public function testSleepPausesExecution(): void
	{
		$start = microtime( true );
		$this->clock->sleep( 1 );
		$elapsed = microtime( true ) - $start;

		// Allow 100ms tolerance
		$this->assertGreaterThanOrEqual( 0.9, $elapsed );
		$this->assertLessThanOrEqual( 1.1, $elapsed );
	}

	public function testUsleepPausesExecution(): void
	{
		$start = microtime( true );
		$this->clock->usleep( 100000 ); // 0.1 seconds
		$elapsed = microtime( true ) - $start;

		// Allow 50ms tolerance
		$this->assertGreaterThanOrEqual( 0.05, $elapsed );
		$this->assertLessThanOrEqual( 0.15, $elapsed );
	}
}
