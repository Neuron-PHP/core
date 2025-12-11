<?php

namespace Neuron\Core\System;

/**
 * Real clock implementation using PHP native time functions.
 *
 * This is the production implementation that uses actual system time.
 */
class RealClock implements IClock
{
	/**
	 * @inheritDoc
	 */
	public function time(): int
	{
		return time();
	}

	/**
	 * @inheritDoc
	 */
	public function microtime( bool $asFloat = false ): string|float
	{
		return microtime( $asFloat );
	}

	/**
	 * @inheritDoc
	 */
	public function date( string $format, ?int $timestamp = null ): string
	{
		if( $timestamp === null )
		{
			return date( $format );
		}

		return date( $format, $timestamp );
	}

	/**
	 * @inheritDoc
	 */
	public function now(): \DateTimeImmutable
	{
		return new \DateTimeImmutable();
	}

	/**
	 * @inheritDoc
	 */
	public function sleep( int $seconds ): void
	{
		sleep( $seconds );
	}

	/**
	 * @inheritDoc
	 */
	public function usleep( int $microseconds ): void
	{
		usleep( $microseconds );
	}
}
