<?php

namespace Neuron\Core\System;

/**
 * Frozen clock implementation for testing.
 *
 * Provides a fixed point in time that doesn't change until explicitly set.
 * Perfect for testing time-dependent logic deterministically.
 */
class FrozenClock implements IClock
{
	private int $frozenTime;
	private float $frozenMicrotime;

	/**
	 * Create a frozen clock at the specified time
	 *
	 * @param int|null $timestamp Unix timestamp to freeze at (null = current time)
	 * @param float|null $microtime Microtime to freeze at (null = current microtime)
	 */
	public function __construct( ?int $timestamp = null, ?float $microtime = null )
	{
		$this->frozenTime = $timestamp ?? time();
		$this->frozenMicrotime = $microtime ?? microtime( true );
	}

	/**
	 * Set the frozen time to a new value
	 *
	 * @param int $timestamp Unix timestamp
	 * @param float|null $microtime Microtime (null = use timestamp as float)
	 * @return void
	 */
	public function setTime( int $timestamp, ?float $microtime = null ): void
	{
		$this->frozenTime = $timestamp;
		$this->frozenMicrotime = $microtime ?? (float)$timestamp;
	}

	/**
	 * Advance the frozen time by specified seconds
	 *
	 * @param int $seconds Number of seconds to advance
	 * @return void
	 */
	public function advance( int $seconds ): void
	{
		$this->frozenTime += $seconds;
		$this->frozenMicrotime += (float)$seconds;
	}

	/**
	 * Advance the frozen time by specified microseconds
	 *
	 * @param int $microseconds Number of microseconds to advance
	 * @return void
	 */
	public function advanceMicroseconds( int $microseconds ): void
	{
		$seconds = $microseconds / 1000000;
		$this->frozenTime += (int)$seconds;
		$this->frozenMicrotime += $seconds;
	}

	/**
	 * @inheritDoc
	 */
	public function time(): int
	{
		return $this->frozenTime;
	}

	/**
	 * @inheritDoc
	 */
	public function microtime( bool $asFloat = false ): string|float
	{
		if( $asFloat )
		{
			return $this->frozenMicrotime;
		}

		// Format as "msec sec" string like real microtime()
		$sec = floor( $this->frozenMicrotime );
		$msec = $this->frozenMicrotime - $sec;

		return sprintf( "%.6f %d", $msec, (int)$sec );
	}

	/**
	 * @inheritDoc
	 */
	public function date( string $format, ?int $timestamp = null ): string
	{
		$timestamp = $timestamp ?? $this->frozenTime;
		return date( $format, $timestamp );
	}

	/**
	 * @inheritDoc
	 */
	public function now(): \DateTimeImmutable
	{
		return ( new \DateTimeImmutable() )->setTimestamp( $this->frozenTime );
	}

	/**
	 * @inheritDoc
	 * Note: Does NOT actually sleep, just advances the frozen time
	 */
	public function sleep( int $seconds ): void
	{
		$this->advance( $seconds );
	}

	/**
	 * @inheritDoc
	 * Note: Does NOT actually sleep, just advances the frozen time
	 */
	public function usleep( int $microseconds ): void
	{
		$this->advanceMicroseconds( $microseconds );
	}
}
