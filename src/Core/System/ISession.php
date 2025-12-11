<?php

namespace Neuron\Core\System;

/**
 * Interface for session management abstraction.
 *
 * Provides a testable abstraction over PHP session operations. Implementations
 * can be real sessions for production or in-memory sessions for testing.
 */
interface ISession
{
	/**
	 * Start the session
	 *
	 * @return void
	 */
	public function start(): void;

	/**
	 * Regenerate session ID (prevents session fixation attacks)
	 *
	 * @param bool $deleteOld Whether to delete old session file
	 * @return bool Success status
	 */
	public function regenerate( bool $deleteOld = true ): bool;

	/**
	 * Destroy the session completely
	 *
	 * @return bool Success status
	 */
	public function destroy(): bool;

	/**
	 * Check if a key exists in session
	 *
	 * @param string $key Session key
	 * @return bool True if key exists
	 */
	public function has( string $key ): bool;

	/**
	 * Get value from session
	 *
	 * @param string $key Session key
	 * @param mixed $default Default value if key doesn't exist
	 * @return mixed Session value or default
	 */
	public function get( string $key, mixed $default = null ): mixed;

	/**
	 * Set value in session
	 *
	 * @param string $key Session key
	 * @param mixed $value Value to store
	 * @return void
	 */
	public function set( string $key, mixed $value ): void;

	/**
	 * Remove key from session
	 *
	 * @param string $key Session key
	 * @return void
	 */
	public function remove( string $key ): void;

	/**
	 * Clear all session data
	 *
	 * @return void
	 */
	public function clear(): void;

	/**
	 * Set flash message (available only on next request)
	 *
	 * @param string $key Flash key
	 * @param mixed $value Flash value
	 * @return void
	 */
	public function flash( string $key, mixed $value ): void;

	/**
	 * Get flash message (automatically removed after retrieval)
	 *
	 * @param string $key Flash key
	 * @param mixed $default Default value if flash doesn't exist
	 * @return mixed Flash value or default
	 */
	public function getFlash( string $key, mixed $default = null ): mixed;

	/**
	 * Get session ID
	 *
	 * @return string Session ID
	 */
	public function getId(): string;

	/**
	 * Check if session is started
	 *
	 * @return bool True if session is active
	 */
	public function isStarted(): bool;

	/**
	 * Get all session data
	 *
	 * @return array All session data
	 */
	public function all(): array;
}
