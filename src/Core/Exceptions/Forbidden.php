<?php

namespace Neuron\Core\Exceptions;

use Exception;
use Throwable;

/**
 * Exception thrown when an authenticated user lacks permission to access a resource.
 *
 * This exception should be thrown when a user is authenticated but does not have
 * the necessary permissions to access a resource. It corresponds to HTTP 403 Forbidden
 * status code.
 *
 * @package Neuron\Core\Exceptions
 *
 * @example
 * ```php
 * // Throw when user lacks specific permission
 * if (!$user->hasPermission('admin.edit')) {
 *     throw new Forbidden('You do not have permission to edit', 'User', 'admin.edit');
 * }
 *
 * // Throw when accessing forbidden resource
 * if (!$this->canAccessResource($user, $document)) {
 *     throw new Forbidden('Access denied to document', 'Document #' . $document->id);
 * }
 * ```
 */
class Forbidden extends Exception
{
	private ?string $resource;
	private ?string $permission;

	/**
	 * @param string $message The error message
	 * @param string|null $resource The resource that was forbidden (e.g., 'User Profile', 'Document #123')
	 * @param string|null $permission The permission that was lacking (e.g., 'admin.edit', 'document.read')
	 * @param int $code The exception code (defaults to 403)
	 * @param Throwable|null $previous Previous exception for chaining
	 */
	public function __construct(
		string $message = 'Access forbidden',
		?string $resource = null,
		?string $permission = null,
		int $code = 403,
		?Throwable $previous = null
	) {
		$this->resource = $resource;
		$this->permission = $permission;
		parent::__construct( $message, $code, $previous );
	}

	/**
	 * Get the resource that was forbidden
	 *
	 * @return string|null
	 */
	public function getResource(): ?string
	{
		return $this->resource;
	}

	/**
	 * Get the permission that was lacking
	 *
	 * @return string|null
	 */
	public function getPermission(): ?string
	{
		return $this->permission;
	}
}