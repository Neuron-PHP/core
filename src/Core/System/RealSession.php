<?php

namespace Neuron\Core\System;

/**
 * Real session implementation using PHP native session functions.
 *
 * This is the production implementation that uses actual PHP sessions.
 */
class RealSession implements ISession
{
	private const FLASH_KEY = '_flash';
	private const FLASH_NEW_KEY = '_flash_new';

	/**
	 * @inheritDoc
	 */
	public function start(): void
	{
		if( $this->isStarted() )
		{
			return;
		}

		session_start();

		// Age flash data
		$this->ageFlashData();
	}

	/**
	 * @inheritDoc
	 */
	public function regenerate( bool $deleteOld = true ): bool
	{
		$this->start();
		return session_regenerate_id( $deleteOld );
	}

	/**
	 * @inheritDoc
	 */
	public function destroy(): bool
	{
		$this->start();

		$_SESSION = [];

		// Delete session cookie
		if( isset( $_COOKIE[session_name()] ) )
		{
			$params = session_get_cookie_params();
			setcookie(
				session_name(),
				'',
				time() - 42000,
				$params['path'],
				$params['domain'],
				$params['secure'],
				$params['httponly']
			);
		}

		return session_destroy();
	}

	/**
	 * @inheritDoc
	 */
	public function has( string $key ): bool
	{
		$this->start();
		return isset( $_SESSION[$key] );
	}

	/**
	 * @inheritDoc
	 */
	public function get( string $key, mixed $default = null ): mixed
	{
		$this->start();
		return $_SESSION[$key] ?? $default;
	}

	/**
	 * @inheritDoc
	 */
	public function set( string $key, mixed $value ): void
	{
		$this->start();
		$_SESSION[$key] = $value;
	}

	/**
	 * @inheritDoc
	 */
	public function remove( string $key ): void
	{
		$this->start();
		unset( $_SESSION[$key] );
	}

	/**
	 * @inheritDoc
	 */
	public function clear(): void
	{
		$this->start();
		$_SESSION = [];
	}

	/**
	 * @inheritDoc
	 */
	public function flash( string $key, mixed $value ): void
	{
		$this->start();

		if( !isset( $_SESSION[self::FLASH_NEW_KEY] ) )
		{
			$_SESSION[self::FLASH_NEW_KEY] = [];
		}

		$_SESSION[self::FLASH_NEW_KEY][$key] = $value;
	}

	/**
	 * @inheritDoc
	 */
	public function getFlash( string $key, mixed $default = null ): mixed
	{
		$this->start();

		$value = $_SESSION[self::FLASH_KEY][$key] ?? $default;

		// Remove flash after retrieval
		if( isset( $_SESSION[self::FLASH_KEY][$key] ) )
		{
			unset( $_SESSION[self::FLASH_KEY][$key] );
		}

		return $value;
	}

	/**
	 * @inheritDoc
	 */
	public function getId(): string
	{
		$this->start();
		return session_id();
	}

	/**
	 * @inheritDoc
	 */
	public function isStarted(): bool
	{
		return session_status() === PHP_SESSION_ACTIVE;
	}

	/**
	 * @inheritDoc
	 */
	public function all(): array
	{
		$this->start();
		return $_SESSION;
	}

	/**
	 * Age flash data - promote newly written flashes so they become readable
	 * on this (the next) request.
	 *
	 * Existing FLASH_KEY data is deliberately preserved: other components
	 * (e.g. the CMS SessionManager) write flash messages directly to
	 * FLASH_KEY with delete-on-read semantics, and this method runs on the
	 * first session start of every request - clearing FLASH_KEY here would
	 * destroy those messages before they could ever be displayed. Flashes
	 * are removed by getFlash() when they are read instead.
	 *
	 * @return void
	 */
	private function ageFlashData(): void
	{
		// Promote new flash data, merging over any directly written flashes
		if( isset( $_SESSION[self::FLASH_NEW_KEY] ) )
		{
			$_SESSION[self::FLASH_KEY] = array_merge(
				$_SESSION[self::FLASH_KEY] ?? [],
				$_SESSION[self::FLASH_NEW_KEY]
			);

			unset( $_SESSION[self::FLASH_NEW_KEY] );
		}
	}
}
