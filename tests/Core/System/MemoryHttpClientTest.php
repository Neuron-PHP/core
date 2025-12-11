<?php

namespace Tests\Core\System;

use Neuron\Core\System\HttpResponse;
use Neuron\Core\System\IHttpClient;
use Neuron\Core\System\MemoryHttpClient;
use PHPUnit\Framework\TestCase;

/**
 * Tests for MemoryHttpClient implementation
 */
class MemoryHttpClientTest extends TestCase
{
	private MemoryHttpClient $client;

	protected function setUp(): void
	{
		$this->client = new MemoryHttpClient();
	}

	public function testImplementsInterface(): void
	{
		$this->assertInstanceOf( IHttpClient::class, $this->client );
	}

	public function testGetRequestIsRecorded(): void
	{
		$this->client->addSuccessResponse( 'https://example.com/api', 'test' );

		$this->client->get( 'https://example.com/api' );

		$requests = $this->client->getRequests();
		$this->assertCount( 1, $requests );
		$this->assertEquals( 'GET', $requests[0]['method'] );
		$this->assertEquals( 'https://example.com/api', $requests[0]['url'] );
	}

	public function testPostRequestIsRecorded(): void
	{
		$this->client->addSuccessResponse( 'https://example.com/api', 'test' );

		$this->client->post( 'https://example.com/api', ['key' => 'value'] );

		$requests = $this->client->getRequests();
		$this->assertCount( 1, $requests );
		$this->assertEquals( 'POST', $requests[0]['method'] );
		$this->assertEquals( ['key' => 'value'], $requests[0]['data'] );
	}

	public function testGetWithQueryParams(): void
	{
		$this->client->addSuccessResponse( 'https://example.com/api*', 'test' );

		$this->client->get( 'https://example.com/api', ['foo' => 'bar', 'baz' => 'qux'] );

		$lastRequest = $this->client->getLastRequest();
		$this->assertStringContainsString( 'foo=bar', $lastRequest['url'] );
		$this->assertStringContainsString( 'baz=qux', $lastRequest['url'] );
	}

	public function testAddSuccessResponse(): void
	{
		$this->client->addSuccessResponse( 'https://example.com/test', 'Success response' );

		$response = $this->client->get( 'https://example.com/test' );

		$this->assertTrue( $response->isSuccess() );
		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertEquals( 'Success response', $response->getBody() );
	}

	public function testAddSuccessResponseWithCustomStatusCode(): void
	{
		$this->client->addSuccessResponse( 'https://example.com/created', 'Created', 201 );

		$response = $this->client->post( 'https://example.com/created' );

		$this->assertEquals( 201, $response->getStatusCode() );
		$this->assertEquals( 'Created', $response->getBody() );
	}

	public function testAddErrorResponse(): void
	{
		$this->client->addErrorResponse( 'https://example.com/error', 'Connection timeout', 28 );

		$response = $this->client->get( 'https://example.com/error' );

		$this->assertTrue( $response->hasError() );
		$this->assertEquals( 28, $response->getErrorCode() );
		$this->assertEquals( 'Connection timeout', $response->getError() );
	}

	public function testAddResponseWithCustomResponse(): void
	{
		$customResponse = new HttpResponse( 418, "I'm a teapot" );
		$this->client->addResponse( 'https://example.com/teapot', $customResponse );

		$response = $this->client->get( 'https://example.com/teapot' );

		$this->assertEquals( 418, $response->getStatusCode() );
		$this->assertEquals( "I'm a teapot", $response->getBody() );
	}

	public function testResponseForSpecificMethod(): void
	{
		$this->client->addSuccessResponse( 'https://example.com/api', 'GET response', 200, 'GET' );
		$this->client->addSuccessResponse( 'https://example.com/api', 'POST response', 201, 'POST' );

		$getResponse = $this->client->get( 'https://example.com/api' );
		$postResponse = $this->client->post( 'https://example.com/api' );

		$this->assertEquals( 'GET response', $getResponse->getBody() );
		$this->assertEquals( 'POST response', $postResponse->getBody() );
	}

	public function testWildcardMethod(): void
	{
		$this->client->addSuccessResponse( 'https://example.com/any', 'Any method', 200, '*' );

		$getResponse = $this->client->get( 'https://example.com/any' );
		$postResponse = $this->client->post( 'https://example.com/any' );

		$this->assertEquals( 'Any method', $getResponse->getBody() );
		$this->assertEquals( 'Any method', $postResponse->getBody() );
	}

	public function testWildcardUrlPattern(): void
	{
		$this->client->addSuccessResponse( 'https://example.com/api/*', 'Matched' );

		$response1 = $this->client->get( 'https://example.com/api/users' );
		$response2 = $this->client->get( 'https://example.com/api/posts' );

		$this->assertEquals( 'Matched', $response1->getBody() );
		$this->assertEquals( 'Matched', $response2->getBody() );
	}

	public function testDefault404ResponseForUnmatched(): void
	{
		$response = $this->client->get( 'https://example.com/not-found' );

		$this->assertEquals( 404, $response->getStatusCode() );
		$this->assertEquals( 'Not Found', $response->getBody() );
	}

	public function testPostJsonWithHeaders(): void
	{
		$this->client->addSuccessResponse( 'https://example.com/api', 'OK' );

		$this->client->postJson( 'https://example.com/api', '{"key":"value"}' );

		$lastRequest = $this->client->getLastRequest();
		$this->assertEquals( 'POST', $lastRequest['method'] );
		$this->assertEquals( 'application/json', $lastRequest['headers']['Content-Type'] );
		$this->assertEquals( '15', $lastRequest['headers']['Content-Length'] );
	}

	public function testPutRequest(): void
	{
		$this->client->addSuccessResponse( 'https://example.com/resource', 'Updated' );

		$response = $this->client->put( 'https://example.com/resource', ['name' => 'Test'] );

		$this->assertEquals( 'Updated', $response->getBody() );

		$lastRequest = $this->client->getLastRequest();
		$this->assertEquals( 'PUT', $lastRequest['method'] );
	}

	public function testDeleteRequest(): void
	{
		$this->client->addSuccessResponse( 'https://example.com/resource', '' );

		$response = $this->client->delete( 'https://example.com/resource' );

		$lastRequest = $this->client->getLastRequest();
		$this->assertEquals( 'DELETE', $lastRequest['method'] );
	}

	public function testSetDefaultHeaders(): void
	{
		$this->client->setDefaultHeaders( ['Authorization' => 'Bearer token123'] );
		$this->client->addSuccessResponse( 'https://example.com/api', 'OK' );

		$this->client->get( 'https://example.com/api', [], ['X-Custom' => 'value'] );

		$lastRequest = $this->client->getLastRequest();
		$this->assertEquals( 'Bearer token123', $lastRequest['headers']['Authorization'] );
		$this->assertEquals( 'value', $lastRequest['headers']['X-Custom'] );
	}

	public function testSetTimeout(): void
	{
		// Timeout doesn't affect memory client, but method should exist
		$this->client->setTimeout( 30 );

		$this->addToAssertionCount( 1 );
	}

	public function testGetLastRequest(): void
	{
		$this->client->addSuccessResponse( 'https://example.com/*', 'OK' );

		$this->client->get( 'https://example.com/first' );
		$this->client->post( 'https://example.com/second' );

		$lastRequest = $this->client->getLastRequest();

		$this->assertEquals( 'POST', $lastRequest['method'] );
		$this->assertEquals( 'https://example.com/second', $lastRequest['url'] );
	}

	public function testGetLastRequestWhenNoRequests(): void
	{
		$lastRequest = $this->client->getLastRequest();

		$this->assertNull( $lastRequest );
	}

	public function testClearRequests(): void
	{
		$this->client->addSuccessResponse( 'https://example.com/api', 'OK' );

		$this->client->get( 'https://example.com/api' );
		$this->assertCount( 1, $this->client->getRequests() );

		$this->client->clearRequests();

		$this->assertCount( 0, $this->client->getRequests() );
	}

	public function testMultipleRequestsRecorded(): void
	{
		$this->client->addSuccessResponse( 'https://example.com/*', 'OK' );

		$this->client->get( 'https://example.com/one' );
		$this->client->post( 'https://example.com/two', ['data' => 'test'] );
		$this->client->put( 'https://example.com/three' );

		$requests = $this->client->getRequests();

		$this->assertCount( 3, $requests );
		$this->assertEquals( 'GET', $requests[0]['method'] );
		$this->assertEquals( 'POST', $requests[1]['method'] );
		$this->assertEquals( 'PUT', $requests[2]['method'] );
	}

	public function testPatternMatchingPriority(): void
	{
		// Exact match should take priority over wildcard
		$this->client->addSuccessResponse( 'https://example.com/api/users', 'Exact match' );
		$this->client->addSuccessResponse( 'https://example.com/api/*', 'Wildcard match' );

		$exactResponse = $this->client->get( 'https://example.com/api/users' );
		$wildcardResponse = $this->client->get( 'https://example.com/api/posts' );

		$this->assertEquals( 'Exact match', $exactResponse->getBody() );
		$this->assertEquals( 'Wildcard match', $wildcardResponse->getBody() );
	}

	public function testMethodSpecificMatchOverWildcard(): void
	{
		$this->client->addSuccessResponse( 'https://example.com/api', 'GET specific', 200, 'GET' );
		$this->client->addSuccessResponse( 'https://example.com/api', 'Any method', 200, '*' );

		$getResponse = $this->client->get( 'https://example.com/api' );
		$postResponse = $this->client->post( 'https://example.com/api' );

		$this->assertEquals( 'GET specific', $getResponse->getBody() );
		$this->assertEquals( 'Any method', $postResponse->getBody() );
	}
}
