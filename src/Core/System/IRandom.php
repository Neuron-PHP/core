<?php

namespace Neuron\Core\System;

/**
 * Interface for random generation abstraction.
 *
 * Provides a testable abstraction over random operations. Implementations
 * can be cryptographically secure for production or predictable for testing.
 */
interface IRandom
{
	/**
	 * Generate cryptographically secure random bytes
	 *
	 * @param int $length Number of bytes to generate
	 * @return string Random bytes
	 * @throws \Exception If random source is not available
	 */
	public function bytes( int $length ): string;

	/**
	 * Generate random integer in range (inclusive)
	 *
	 * @param int $min Minimum value (inclusive)
	 * @param int $max Maximum value (inclusive)
	 * @return int Random integer
	 */
	public function int( int $min, int $max ): int;

	/**
	 * Generate unique identifier
	 *
	 * @param string $prefix Prefix for the unique ID
	 * @return string Unique identifier
	 */
	public function uniqueId( string $prefix = '' ): string;

	/**
	 * Generate random string using specified charset
	 *
	 * @param int $length Length of the string
	 * @param string $charset Character set ('hex', 'base64', 'alphanumeric', 'alpha', 'numeric')
	 * @return string Random string
	 */
	public function string( int $length, string $charset = 'hex' ): string;

	/**
	 * Generate random float between 0 and 1
	 *
	 * @return float Random float between 0.0 and 1.0
	 */
	public function float(): float;

	/**
	 * Shuffle an array randomly
	 *
	 * @param array $array Array to shuffle
	 * @return array Shuffled array
	 */
	public function shuffle( array $array ): array;
}
