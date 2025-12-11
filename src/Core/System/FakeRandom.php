<?php

namespace Neuron\Core\System;

/**
 * Fake random implementation for testing.
 *
 * Provides predictable, deterministic "random" values for testing.
 * Can be seeded with specific values or use a predictable sequence.
 */
class FakeRandom implements IRandom
{
	private array $byteSequence = [];
	private array $intSequence = [];
	private array $uniqueIdSequence = [];
	private int $seed = 0;

	/**
	 * Set sequence of bytes to return from bytes() method
	 *
	 * @param array $sequence Array of byte strings
	 * @return void
	 */
	public function setByteSequence( array $sequence ): void
	{
		$this->byteSequence = $sequence;
	}

	/**
	 * Set sequence of integers to return from int() method
	 *
	 * @param array $sequence Array of integers
	 * @return void
	 */
	public function setIntSequence( array $sequence ): void
	{
		$this->intSequence = $sequence;
	}

	/**
	 * Set sequence of unique IDs to return from uniqueId() method
	 *
	 * @param array $sequence Array of unique ID strings
	 * @return void
	 */
	public function setUniqueIdSequence( array $sequence ): void
	{
		$this->uniqueIdSequence = $sequence;
	}

	/**
	 * Set seed for predictable generation
	 *
	 * @param int $seed Seed value
	 * @return void
	 */
	public function setSeed( int $seed ): void
	{
		$this->seed = $seed;
	}

	/**
	 * @inheritDoc
	 */
	public function bytes( int $length ): string
	{
		if( !empty( $this->byteSequence ) )
		{
			return array_shift( $this->byteSequence );
		}

		// Generate predictable bytes based on seed
		$result = '';
		for( $i = 0; $i < $length; $i++ )
		{
			$result .= chr( ( $this->seed + $i ) % 256 );
		}

		return $result;
	}

	/**
	 * @inheritDoc
	 */
	public function int( int $min, int $max ): int
	{
		if( !empty( $this->intSequence ) )
		{
			return array_shift( $this->intSequence );
		}

		// Generate predictable int in range based on seed
		$range = $max - $min + 1;
		return $min + ( $this->seed % $range );
	}

	/**
	 * @inheritDoc
	 */
	public function uniqueId( string $prefix = '' ): string
	{
		if( !empty( $this->uniqueIdSequence ) )
		{
			return $prefix . array_shift( $this->uniqueIdSequence );
		}

		// Generate predictable unique ID
		$this->seed++;
		return $prefix . sprintf( '%08x%05x', $this->seed, $this->seed );
	}

	/**
	 * @inheritDoc
	 */
	public function string( int $length, string $charset = 'hex' ): string
	{
		switch( $charset )
		{
			case 'hex':
				return substr( bin2hex( $this->bytes( $length ) ), 0, $length );

			case 'base64':
				$bytes = $this->bytes( (int)ceil( $length * 3 / 4 ) );
				return substr( base64_encode( $bytes ), 0, $length );

			case 'alphanumeric':
				$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
				return $this->stringFromCharset( $length, $chars );

			case 'alpha':
				$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
				return $this->stringFromCharset( $length, $chars );

			case 'numeric':
				$chars = '0123456789';
				return $this->stringFromCharset( $length, $chars );

			default:
				throw new \InvalidArgumentException( "Unknown charset: $charset" );
		}
	}

	/**
	 * @inheritDoc
	 */
	public function float(): float
	{
		return ( $this->seed % 1000 ) / 1000.0;
	}

	/**
	 * @inheritDoc
	 */
	public function shuffle( array $array ): array
	{
		// Return predictable "shuffle" - just reverse the array
		return array_reverse( $array );
	}

	/**
	 * Generate string from character set using predictable pattern
	 *
	 * @param int $length Length of string
	 * @param string $chars Available characters
	 * @return string Predictable string
	 */
	private function stringFromCharset( int $length, string $chars ): string
	{
		$result = '';
		$charCount = strlen( $chars );

		for( $i = 0; $i < $length; $i++ )
		{
			$result .= $chars[( $this->seed + $i ) % $charCount];
		}

		return $result;
	}
}
