<?php

namespace Tests\Core\System;

use Neuron\Core\System\HttpResponse;
use Neuron\Core\System\IHttpResponse;
use PHPUnit\Framework\TestCase;

/**
 * Tests for HttpResponse implementation
 */
class HttpResponseTest extends TestCase
{
	public function testImplementsInterface(): void
	{
		$response = new HttpResponse();
		$this->assertInstanceOf( IHttpResponse::class, $response );
	}

	public function testConstructorWithAllParameters(): void
	{
		$response = new HttpResponse(
			200,
			'Success',
			['Content-Type' => 'application/json'],
			0,
			''
		);

		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertEquals( 'Success', $response->getBody() );
		$this->assertEquals( ['Content-Type' => 'application/json'], $response->getHeaders() );
		$this->assertEquals( 0, $response->getErrorCode() );
		$this->assertEquals( '', $response->getError() );
	}

	public function testConstructorWithDefaultParameters(): void
	{
		$response = new HttpResponse();

		$this->assertEquals( 0, $response->getStatusCode() );
		$this->assertEquals( '', $response->getBody() );
		$this->assertEquals( [], $response->getHeaders() );
		$this->assertEquals( 0, $response->getErrorCode() );
		$this->assertEquals( '', $response->getError() );
	}

	public function testGetStatusCode(): void
	{
		$response = new HttpResponse( 404 );

		$this->assertEquals( 404, $response->getStatusCode() );
	}

	public function testGetBody(): void
	{
		$response = new HttpResponse( 200, '{"id":1,"name":"Test"}' );

		$this->assertEquals( '{"id":1,"name":"Test"}', $response->getBody() );
	}

	public function testGetHeaders(): void
	{
		$headers = [
			'Content-Type' => 'application/json',
			'X-Custom-Header' => 'value'
		];
		$response = new HttpResponse( 200, '', $headers );

		$this->assertEquals( $headers, $response->getHeaders() );
	}

	public function testGetHeader(): void
	{
		$headers = [
			'Content-Type' => 'application/json',
			'X-Custom-Header' => 'value'
		];
		$response = new HttpResponse( 200, '', $headers );

		$this->assertEquals( 'application/json', $response->getHeader( 'Content-Type' ) );
		$this->assertEquals( 'value', $response->getHeader( 'X-Custom-Header' ) );
	}

	public function testGetHeaderCaseInsensitive(): void
	{
		$headers = ['Content-Type' => 'application/json'];
		$response = new HttpResponse( 200, '', $headers );

		$this->assertEquals( 'application/json', $response->getHeader( 'content-type' ) );
		$this->assertEquals( 'application/json', $response->getHeader( 'CONTENT-TYPE' ) );
		$this->assertEquals( 'application/json', $response->getHeader( 'Content-Type' ) );
	}

	public function testGetHeaderNonExistent(): void
	{
		$response = new HttpResponse( 200, '', ['Content-Type' => 'text/html'] );

		$this->assertNull( $response->getHeader( 'X-Missing-Header' ) );
	}

	public function testIsSuccessForTwoHundredStatusCodes(): void
	{
		$this->assertTrue( ( new HttpResponse( 200 ) )->isSuccess() );
		$this->assertTrue( ( new HttpResponse( 201 ) )->isSuccess() );
		$this->assertTrue( ( new HttpResponse( 204 ) )->isSuccess() );
		$this->assertTrue( ( new HttpResponse( 299 ) )->isSuccess() );
	}

	public function testIsSuccessForNonTwoHundredStatusCodes(): void
	{
		$this->assertFalse( ( new HttpResponse( 199 ) )->isSuccess() );
		$this->assertFalse( ( new HttpResponse( 300 ) )->isSuccess() );
		$this->assertFalse( ( new HttpResponse( 400 ) )->isSuccess() );
		$this->assertFalse( ( new HttpResponse( 404 ) )->isSuccess() );
		$this->assertFalse( ( new HttpResponse( 500 ) )->isSuccess() );
	}

	public function testHasErrorWhenErrorCodeIsZero(): void
	{
		$response = new HttpResponse( 200, '', [], 0, '' );

		$this->assertFalse( $response->hasError() );
	}

	public function testHasErrorWhenErrorCodeIsNonZero(): void
	{
		$response = new HttpResponse( 0, '', [], 28, 'Connection timeout' );

		$this->assertTrue( $response->hasError() );
	}

	public function testGetError(): void
	{
		$response = new HttpResponse( 0, '', [], 7, 'Failed to connect' );

		$this->assertEquals( 'Failed to connect', $response->getError() );
	}

	public function testGetErrorCode(): void
	{
		$response = new HttpResponse( 0, '', [], 28, 'Timeout' );

		$this->assertEquals( 28, $response->getErrorCode() );
	}

	public function testSuccessResponseWithoutError(): void
	{
		$response = new HttpResponse(
			200,
			'{"status":"ok"}',
			['Content-Type' => 'application/json'],
			0,
			''
		);

		$this->assertTrue( $response->isSuccess() );
		$this->assertFalse( $response->hasError() );
		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertEquals( '{"status":"ok"}', $response->getBody() );
	}

	public function testErrorResponseWithConnectionError(): void
	{
		$response = new HttpResponse(
			0,
			'',
			[],
			7,
			'Failed to connect to host'
		);

		$this->assertFalse( $response->isSuccess() );
		$this->assertTrue( $response->hasError() );
		$this->assertEquals( 0, $response->getStatusCode() );
		$this->assertEquals( 7, $response->getErrorCode() );
		$this->assertEquals( 'Failed to connect to host', $response->getError() );
	}

	public function testFailedResponseWithoutConnectionError(): void
	{
		$response = new HttpResponse(
			404,
			'Not Found',
			[],
			0,
			''
		);

		$this->assertFalse( $response->isSuccess() );
		$this->assertFalse( $response->hasError() );
		$this->assertEquals( 404, $response->getStatusCode() );
	}
}
