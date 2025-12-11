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
	 * Age flash data - move new flash to old, remove old flash
	 *
	 * @return void
	 */
	private function ageFlashData(): void
	{
		// Remove old flash data
		if( isset( $this->data[self::FLASH_KEY] ) )
		{
			unset( $this->data[self::FLASH_KEY] );
		}

		// Move new flash to old
		if( isset( $this->data[self::FLASH_NEW_KEY] ) )
		{
			$this->data[self::FLASH_KEY] = $this->data[self::FLASH_NEW_KEY];
			unset( $this->data[self::FLASH_NEW_KEY] );
		}
	}
}
