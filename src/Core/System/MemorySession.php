<?php

namespace Neuron\Core\System;

/**
 * In-memory session implementation for testing.
 *
 * Provides a virtual session that exists entirely in memory.
 * Perfect for testing session-dependent code without actual PHP sessions.
 */
class MemorySession implements ISession
{
	private const FLASH_KEY = '_flash';
	private const FLASH_NEW_KEY = '_flash_new';

	private array $data = [];
	private bool $started = false;
	private string $id;

	/**
	 * Create memory session with optional ID
	 *
	 * @param string|null $id Session ID (null = generate random)
	 */
	public function __construct( ?string $id = null )
	{
		$this->id = $id ?? bin2hex( random_bytes( 16 ) );
	}

	/**
	 * @inheritDoc
	 */
	public function start(): void
	{
		if( $this->started )
		{
			return;
		}

		$this->started = true;

		// Age flash data on start
		$this->ageFlashData();
	}

	/**
	 * @inheritDoc
	 */
	public function regenerate( bool $deleteOld = true ): bool
	{
		$this->start();

		// Generate new ID
		$this->id = bin2hex( random_bytes( 16 ) );

		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function destroy(): bool
	{
		$this->data = [];
		$this->started = false;
		$this->id = bin2hex( random_bytes( 16 ) );

		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function has( string $key ): bool
	{
		$this->start();
		return isset( $this->data[$key] );
	}

	/**
	 * @inheritDoc
	 */
	public function get( string $key, mixed $default = null ): mixed
	{
		$this->start();
		return $this->data[$key] ?? $default;
	}

	/**
	 * @inheritDoc
	 */
	public function set( string $key, mixed $value ): void
	{
		$this->start();
		$this->data[$key] = $value;
	}

	/**
	 * @inheritDoc
	 */
	public function remove( string $key ): void
	{
		$this->start();
		unset( $this->data[$key] );
	}

	/**
	 * @inheritDoc
	 */
	public function clear(): void
	{
		$this->start();
		$this->data = [];
	}

	/**
	 * @inheritDoc
	 */
	public function flash( string $key, mixed $value ): void
	{
		$this->start();

		if( !isset( $this->data[self::FLASH_NEW_KEY] ) )
		{
			$this->data[self::FLASH_NEW_KEY] = [];
		}

		$this->data[self::FLASH_NEW_KEY][$key] = $value;
	}

	/**
	 * @inheritDoc
	 */
	public function getFlash( string $key, mixed $default = null ): mixed
	{
		$this->start();

		$value = $this->data[self::FLASH_KEY][$key] ?? $default;

		// Remove flash after retrieval
		if( isset( $this->data[self::FLASH_KEY][$key] ) )
		{
			unset( $this->data[self::FLASH_KEY][$key] );
		}

		return $value;
	}

	/**
	 * @inheritDoc
	 */
	public function getId(): string
	{
		$this->start();
		return $this->id;
	}

	/**
	 * @inheritDoc
	 */
	public function isStarted(): bool
	{
		return $this->started;
	}

	/**
	 * @inheritDoc
	 */
	public function all(): array
	{
		$this->start();
		return $this->data;
	}

	/**
	 * Age flash data - promote newly written flashes so they become readable
	 * on this (the next) request.
	 *
	 * Existing FLASH_KEY data is preserved (matching RealSession): other
	 * components may write flash messages directly to FLASH_KEY with
	 * delete-on-read semantics. Flashes are removed by getFlash() when read.
	 *
	 * @return void
	 */
	private function ageFlashData(): void
	{
		// Promote new flash data, merging over any directly written flashes
		if( isset( $this->data[self::FLASH_NEW_KEY] ) )
		{
			$this->data[self::FLASH_KEY] = array_merge(
				$this->data[self::FLASH_KEY] ?? [],
				$this->data[self::FLASH_NEW_KEY]
			);

			unset( $this->data[self::FLASH_NEW_KEY] );
		}
	}
}
