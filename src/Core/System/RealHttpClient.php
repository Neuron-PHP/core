<?php

namespace Neuron\Core\System;

/**
 * Real HTTP client implementation using curl.
 *
 * This is the production implementation that makes actual HTTP requests.
 */
class RealHttpClient implements IHttpClient
{
	private int $timeout = 30;
	private array $defaultHeaders = [];

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

		$ch = $this->initCurl( $url, $headers );

		return $this->executeRequest( $ch );
	}

	/**
	 * @inheritDoc
	 */
	public function post( string $url, array $data = [], array $headers = [] ): IHttpResponse
	{
		$ch = $this->initCurl( $url, $headers );

		curl_setopt_array( $ch, [
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => $data
		] );

		return $this->executeRequest( $ch );
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

		$ch = $this->initCurl( $url, $headers );

		curl_setopt_array( $ch, [
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => $json
		] );

		return $this->executeRequest( $ch );
	}

	/**
	 * @inheritDoc
	 */
	public function put( string $url, array $data = [], array $headers = [] ): IHttpResponse
	{
		$ch = $this->initCurl( $url, $headers );

		curl_setopt_array( $ch, [
			CURLOPT_CUSTOMREQUEST => 'PUT',
			CURLOPT_POSTFIELDS => http_build_query( $data )
		] );

		return $this->executeRequest( $ch );
	}

	/**
	 * @inheritDoc
	 */
	public function delete( string $url, array $headers = [] ): IHttpResponse
	{
		$ch = $this->initCurl( $url, $headers );

		curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'DELETE' );

		return $this->executeRequest( $ch );
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
	 * Initialize curl handle with common options
	 *
	 * @param string $url Request URL
	 * @param array $headers Request headers
	 * @return resource Curl handle
	 */
	private function initCurl( string $url, array $headers )
	{
		$ch = curl_init();

		$allHeaders = array_merge( $this->defaultHeaders, $headers );
		$headerLines = [];

		foreach( $allHeaders as $name => $value )
		{
			$headerLines[] = "$name: $value";
		}

		curl_setopt_array( $ch, [
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_TIMEOUT => $this->timeout,
			CURLOPT_HEADER => false,
			CURLOPT_HTTPHEADER => $headerLines
		] );

		return $ch;
	}

	/**
	 * Execute curl request and build response
	 *
	 * @param resource $ch Curl handle
	 * @return IHttpResponse Response object
	 */
	private function executeRequest( $ch ): IHttpResponse
	{
		$body = curl_exec( $ch );
		$statusCode = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
		$errorCode = curl_errno( $ch );
		$errorMessage = curl_error( $ch );

		curl_close( $ch );

		return new HttpResponse(
			$statusCode,
			$body !== false ? $body : '',
			[],
			$errorCode,
			$errorMessage
		);
	}
}
