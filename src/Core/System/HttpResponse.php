<?php

namespace Neuron\Core\System;

/**
 * HTTP response implementation.
 *
 * Concrete implementation of IHttpResponse used by both production and test clients.
 */
class HttpResponse implements IHttpResponse
{
	private int $statusCode;
	private string $body;
	private array $headers;
	private int $errorCode;
	private string $errorMessage;

	/**
	 * Create HTTP response
	 *
	 * @param int $statusCode HTTP status code
	 * @param string $body Response body
	 * @param array $headers Response headers
	 * @param int $errorCode Error code (0 = no error)
	 * @param string $errorMessage Error message
	 */
	public function __construct(
		int $statusCode = 0,
		string $body = '',
		array $headers = [],
		int $errorCode = 0,
		string $errorMessage = ''
	)
	{
		$this->statusCode = $statusCode;
		$this->body = $body;
		$this->headers = $headers;
		$this->errorCode = $errorCode;
		$this->errorMessage = $errorMessage;
	}

	/**
	 * @inheritDoc
	 */
	public function getStatusCode(): int
	{
		return $this->statusCode;
	}

	/**
	 * @inheritDoc
	 */
	public function getBody(): string
	{
		return $this->body;
	}

	/**
	 * @inheritDoc
	 */
	public function getHeaders(): array
	{
		return $this->headers;
	}

	/**
	 * @inheritDoc
	 */
	public function getHeader( string $name ): ?string
	{
		// Case-insensitive header lookup
		$lowerName = strtolower( $name );

		foreach( $this->headers as $key => $value )
		{
			if( strtolower( $key ) === $lowerName )
			{
				return $value;
			}
		}

		return null;
	}

	/**
	 * @inheritDoc
	 */
	public function isSuccess(): bool
	{
		return $this->statusCode >= 200 && $this->statusCode < 300;
	}

	/**
	 * @inheritDoc
	 */
	public function hasError(): bool
	{
		return $this->errorCode !== 0;
	}

	/**
	 * @inheritDoc
	 */
	public function getError(): string
	{
		return $this->errorMessage;
	}

	/**
	 * @inheritDoc
	 */
	public function getErrorCode(): int
	{
		return $this->errorCode;
	}
}
