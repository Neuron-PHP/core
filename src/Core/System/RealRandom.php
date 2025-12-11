<?php

namespace Neuron\Core\System;

/**
 * Real random implementation using PHP cryptographically secure functions.
 *
 * This is the production implementation using actual random sources.
 */
class RealRandom implements IRandom
{
	/**
	 * @inheritDoc
	 */
	public function bytes( int $length ): string
	{
		return random_bytes( $length );
	}

	/**
	 * @inheritDoc
	 */
	public function int( int $min, int $max ): int
	{
		return random_int( $min, $max );
	}

	/**
	 * @inheritDoc
	 */
	public function uniqueId( string $prefix = '' ): string
	{
		return uniqid( $prefix, true );
	}

	/**
	 * @inheritDoc
	 */
	public function string( int $length, string $charset = 'hex' ): string
	{
		switch( $charset )
		{
			case 'hex':
				return bin2hex( $this->bytes( (int)ceil( $length / 2 ) ) );

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
		return $this->int( 0, PHP_INT_MAX ) / PHP_INT_MAX;
	}

	/**
	 * @inheritDoc
	 */
	public function shuffle( array $array ): array
	{
		$shuffled = $array;
		shuffle( $shuffled );
		return $shuffled;
	}

	/**
	 * Generate random string from character set
	 *
	 * @param int $length Length of string
	 * @param string $chars Available characters
	 * @return string Random string
	 */
	private function stringFromCharset( int $length, string $chars ): string
	{
		$result = '';
		$charCount = strlen( $chars );

		for( $i = 0; $i < $length; $i++ )
		{
			$result .= $chars[$this->int( 0, $charCount - 1 )];
		}

		return $result;
	}
}
