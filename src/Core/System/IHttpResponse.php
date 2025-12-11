<?php

namespace Neuron\Core\System;

/**
 * Interface for HTTP response abstraction.
 *
 * Represents the response from an HTTP request with status, body, headers, and error info.
 */
interface IHttpResponse
{
	/**
	 * Get HTTP status code
	 *
	 * @return int HTTP status code (200, 404, 500, etc.)
	 */
	public function getStatusCode(): int;

	/**
	 * Get response body
	 *
	 * @return string Response body content
	 */
	public function getBody(): string;

	/**
	 * Get response headers
	 *
	 * @return array Associative array of headers
	 */
	public function getHeaders(): array;

	/**
	 * Get specific header value
	 *
	 * @param string $name Header name
	 * @return string|null Header value or null if not found
	 */
	public function getHeader( string $name ): ?string;

	/**
	 * Check if response is successful (2xx status code)
	 *
	 * @return bool True if status code is 2xx
	 */
	public function isSuccess(): bool;

	/**
	 * Check if request had an error (connection error, timeout, etc.)
	 *
	 * @return bool True if error occurred
	 */
	public function hasError(): bool;

	/**
	 * Get error message
	 *
	 * @return string Error message or empty string if no error
	 */
	public function getError(): string;

	/**
	 * Get error code
	 *
	 * @return int Error code or 0 if no error
	 */
	public function getErrorCode(): int;
}
