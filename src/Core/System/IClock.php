<?php

namespace Neuron\Core\System;

/**
 * Interface for time/clock operations abstraction.
 *
 * Provides a testable abstraction over time-dependent operations. Implementations
 * can be real system clock for production or frozen/mock clocks for testing.
 */
interface IClock
{
	/**
	 * Get current Unix timestamp
	 *
	 * @return int Current timestamp in seconds since Unix epoch
	 */
	public function time(): int;

	/**
	 * Get current timestamp with microseconds
	 *
	 * @param bool $asFloat If true, returns float; otherwise returns string
	 * @return string|float Timestamp with microseconds
	 */
	public function microtime( bool $asFloat = false ): string|float;

	/**
	 * Format a timestamp as a date string
	 *
	 * @param string $format Date format string
	 * @param int|null $timestamp Unix timestamp (null = current time)
	 * @return string Formatted date string
	 */
	public function date( string $format, ?int $timestamp = null ): string;

	/**
	 * Get current DateTime object
	 *
	 * @return \DateTimeImmutable Current date/time
	 */
	public function now(): \DateTimeImmutable;

	/**
	 * Sleep for specified seconds
	 *
	 * @param int $seconds Number of seconds to sleep
	 * @return void
	 */
	public function sleep( int $seconds ): void;

	/**
	 * Sleep for specified microseconds
	 *
	 * @param int $microseconds Number of microseconds to sleep
	 * @return void
	 */
	public function usleep( int $microseconds ): void;
}
