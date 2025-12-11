<?php

namespace Neuron\Core\System;

/**
 * Interface for HTTP client operations abstraction.
 *
 * Provides a testable abstraction over HTTP operations. Implementations
 * can be real HTTP clients for production or mock clients for testing.
 */
interface IHttpClient
{
	/**
	 * Perform GET request
	 *
	 * @param string $url Request URL
	 * @param array $params Query parameters
	 * @param array $headers Additional headers
	 * @return IHttpResponse Response object
	 */
	public function get( string $url, array $params = [], array $headers = [] ): IHttpResponse;

	/**
	 * Perform POST request with form data
	 *
	 * @param string $url Request URL
	 * @param array $data POST data
	 * @param array $headers Additional headers
	 * @return IHttpResponse Response object
	 */
	public function post( string $url, array $data = [], array $headers = [] ): IHttpResponse;

	/**
	 * Perform POST request with JSON body
	 *
	 * @param string $url Request URL
	 * @param string $json JSON string
	 * @param array $headers Additional headers
	 * @return IHttpResponse Response object
	 */
	public function postJson( string $url, string $json, array $headers = [] ): IHttpResponse;

	/**
	 * Perform PUT request
	 *
	 * @param string $url Request URL
	 * @param array $data PUT data
	 * @param array $headers Additional headers
	 * @return IHttpResponse Response object
	 */
	public function put( string $url, array $data = [], array $headers = [] ): IHttpResponse;

	/**
	 * Perform DELETE request
	 *
	 * @param string $url Request URL
	 * @param array $headers Additional headers
	 * @return IHttpResponse Response object
	 */
	public function delete( string $url, array $headers = [] ): IHttpResponse;

	/**
	 * Set timeout for requests
	 *
	 * @param int $seconds Timeout in seconds
	 * @return void
	 */
	public function setTimeout( int $seconds ): void;

	/**
	 * Set default headers for all requests
	 *
	 * @param array $headers Headers to set
	 * @return void
	 */
	public function setDefaultHeaders( array $headers ): void;
}
