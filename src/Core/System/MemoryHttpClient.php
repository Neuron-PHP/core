<?php

namespace Neuron\Core\System;

/**
 * In-memory HTTP client implementation for testing.
 *
 * Provides programmable responses without making actual HTTP requests.
 * Perfect for testing HTTP-dependent code without network calls.
 */
class MemoryHttpClient implements IHttpClient
{
	private array $responses = [];
	private array $requests = [];
	private int $timeout = 30;
	private array $defaultHeaders = [];

	/**
	 * Add a canned response for a specific URL pattern
	 *
	 * @param string $urlPattern URL or URL pattern (supports * wildcard)
	 * @param IHttpResponse $response Response to return
	 * @param string $method HTTP method (GET, POST, etc.) or '*' for any
	 * @return void
	 */
	public function addResponse( string $urlPattern, IHttpResponse $response, string $method = '*' ): void
	{
		$key = strtoupper( $method ) . ':' . $urlPattern;
		$this->responses[$key] = $response;
	}

	/**
	 * Add a simple successful response
	 *
	 * @param string $urlPattern URL pattern
	 * @param string $body Response body
	 * @param int $statusCode Status code (default 200)
	 * @param string $method HTTP method
	 * @return void
	 */
	public function addSuccessResponse( string $urlPattern, string $body = '', int $statusCode = 200, string $method = '*' ): void
	{
		$response = new HttpResponse( $statusCode, $body );
		$this->addResponse( $urlPattern, $response, $method );
	}

	/**
	 * Add an error response
	 *
	 * @param string $urlPattern URL pattern
	 * @param string $errorMessage Error message
	 * @param int $errorCode Error code
	 * @param string $method HTTP method
	 * @return void
	 */
	public function addErrorResponse( string $urlPattern, string $errorMessage, int $errorCode = 1, string $method = '*' ): void
	{
		$response = new HttpResponse( 0, '', [], $errorCode, $errorMessage );
		$this->addResponse( $urlPattern, $response, $method );
	}

	/**
	 * Get all recorded requests
	 *
	 * @return array Array of request info
	 */
	public function getRequests(): array
	{
		return $this->requests;
	}

	/**
	 * Get last recorded request
	 *
	 * @return array|null Request info or null
	 */
	public function getLastRequest(): ?array
	{
		return end( $this->requests ) ?: null;
	}

	/**
	 * Clear all recorded requests
	 *
	 * @return void
	 */
	public function clearRequests(): void
	{
		$this->requests = [];
	}

	/**
	 * @inheritDoc
	 */
	public function get( string $url, array $params = [], array $headers = [] ): IHttpResponse
	{
		if( !empty( $params ) )
		{
			$queryString = http_build_query( $params );
			$url .= ( strpos( $url, '?' ) === false ? '?' : '&' ) . $queryString;
		}

		return $this->recordAndRespond( 'GET', $url, [], $headers );
	}

	/**
	 * @inheritDoc
	 */
	public function post( string $url, array $data = [], array $headers = [] ): IHttpResponse
	{
		return $this->recordAndRespond( 'POST', $url, $data, $headers );
	}

	/**
	 * @inheritDoc
	 */
	public function postJson( string $url, string $json, array $headers = [] ): IHttpResponse
	{
		$headers = array_merge(
			[
				'Content-Type' => 'application/json',
				'Content-Length' => strlen( $json )
			],
			$headers
		);

		return $this->recordAndRespond( 'POST', $url, ['json' => $json], $headers );
	}

	/**
	 * @inheritDoc
	 */
	public function put( string $url, array $data = [], array $headers = [] ): IHttpResponse
	{
		return $this->recordAndRespond( 'PUT', $url, $data, $headers );
	}

	/**
	 * @inheritDoc
	 */
	public function delete( string $url, array $headers = [] ): IHttpResponse
	{
		return $this->recordAndRespond( 'DELETE', $url, [], $headers );
	}

	/**
	 * @inheritDoc
	 */
	public function setTimeout( int $seconds ): void
	{
		$this->timeout = $seconds;
	}

	/**
	 * @inheritDoc
	 */
	public function setDefaultHeaders( array $headers ): void
	{
		$this->defaultHeaders = $headers;
	}

	/**
	 * Record request and return canned response
	 *
	 * @param string $method HTTP method
	 * @param string $url URL
	 * @param array $data Request data
	 * @param array $headers Request headers
	 * @return IHttpResponse Response object
	 */
	private function recordAndRespond( string $method, string $url, array $data, array $headers ): IHttpResponse
	{
		// Record the request
		$this->requests[] = [
			'method' => $method,
			'url' => $url,
			'data' => $data,
			'headers' => array_merge( $this->defaultHeaders, $headers )
		];

		// Find matching response
		$response = $this->findResponse( $method, $url );

		if( $response )
		{
			return $response;
		}

		// Default 404 response if no match
		return new HttpResponse( 404, 'Not Found' );
	}

	/**
	 * Find canned response for method and URL
	 *
	 * @param string $method HTTP method
	 * @param string $url URL
	 * @return IHttpResponse|null Response or null if not found
	 */
	private function findResponse( string $method, string $url ): ?IHttpResponse
	{
		// Try exact match for method
		$key = strtoupper( $method ) . ':' . $url;
		if( isset( $this->responses[$key] ) )
		{
			return $this->responses[$key];
		}

		// Try wildcard method
		$wildcardKey = '*:' . $url;
		if( isset( $this->responses[$wildcardKey] ) )
		{
			return $this->responses[$wildcardKey];
		}

		// Try pattern matching
		foreach( $this->responses as $pattern => $response )
		{
			[$patternMethod, $patternUrl] = explode( ':', $pattern, 2 );

			if( $patternMethod !== '*' && $patternMethod !== strtoupper( $method ) )
			{
				continue;
			}

			// Simple wildcard matching
			$regex = '/^' . str_replace( '\*', '.*', preg_quote( $patternUrl, '/' ) ) . '$/';

			if( preg_match( $regex, $url ) )
			{
				return $response;
			}
		}

		return null;
	}
}
