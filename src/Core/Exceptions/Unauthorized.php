<?php

namespace Neuron\Core\Exceptions;

use Exception;
use Throwable;

/**
 * Exception thrown when authentication is required but not provided or invalid.
 *
 * This exception should be thrown when a user needs to authenticate to access
 * a resource. It corresponds to HTTP 401 Unauthorized status code.
 *
 * @package Neuron\Core\Exceptions
 *
 * @example
 * ```php
 * // Throw when no authentication is provided
 * if (!$request->hasAuthToken()) {
 *     throw new Unauthorized('Authentication required');
 * }
 *
 * // Throw with realm for HTTP Basic Auth
 * if (!$this->isValidCredentials($credentials)) {
 *     throw new Unauthorized('Invalid credentials', 'Admin Area');
 * }
 * ```
 */
class Unauthorized extends Exception
{
	private ?string $realm;

	/**
	 * @param string $message The error message
	 * @param string|null $realm Optional authentication realm (e.g., for WWW-Authenticate header)
	 * @param int $code The exception code (defaults to 401)
	 * @param Throwable|null $previous Previous exception for chaining
	 */
	public function __construct(
		string $message = 'Authentication required',
		?string $realm = null,
		int $code = 401,
		?Throwable $previous = null
	) {
		$this->realm = $realm;
		parent::__construct( $message, $code, $previous );
	}

	/**
	 * Get the authentication realm if specified
	 *
	 * @return string|null
	 */
	public function getRealm(): ?string
	{
		return $this->realm;
	}
}