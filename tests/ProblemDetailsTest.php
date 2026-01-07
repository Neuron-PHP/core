<?php

namespace Neuron\Core\Tests;

use Neuron\Core\ProblemDetails\ProblemDetails;
use Neuron\Core\ProblemDetails\ProblemType;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for ProblemDetails class.
 */
class ProblemDetailsTest extends TestCase
{
	/**
	 * Test creating a basic problem details instance.
	 */
	public function testCreateBasicProblemDetails(): void
	{
		$problem = new ProblemDetails(
			type: '/errors/test',
			title: 'Test Error',
			status: 400
		);

		$this->assertEquals('/errors/test', $problem->getType());
		$this->assertEquals('Test Error', $problem->getTitle());
		$this->assertEquals(400, $problem->getStatus());
		$this->assertNull($problem->getDetail());
		$this->assertNull($problem->getInstance());
		$this->assertEmpty($problem->getExtensions());
	}

	/**
	 * Test creating a problem details instance with all fields.
	 */
	public function testCreateFullProblemDetails(): void
	{
		$problem = new ProblemDetails(
			type: '/errors/validation',
			title: 'Validation Failed',
			status: 400,
			detail: 'The email field is invalid',
			instance: '/api/users/123',
			extensions: [
				'errors' => ['email' => 'Invalid format'],
				'timestamp' => 1234567890
			]
		);

		$this->assertEquals('/errors/validation', $problem->getType());
		$this->assertEquals('Validation Failed', $problem->getTitle());
		$this->assertEquals(400, $problem->getStatus());
		$this->assertEquals('The email field is invalid', $problem->getDetail());
		$this->assertEquals('/api/users/123', $problem->getInstance());
		$this->assertCount(2, $problem->getExtensions());
		$this->assertEquals('Invalid format', $problem->getExtension('errors')['email']);
		$this->assertEquals(1234567890, $problem->getExtension('timestamp'));
	}

	/**
	 * Test JSON serialization.
	 */
	public function testJsonSerialization(): void
	{
		$problem = new ProblemDetails(
			type: '/errors/not-found',
			title: 'Not Found',
			status: 404,
			detail: 'User not found',
			extensions: ['user_id' => 123]
		);

		$json = json_encode($problem);
		$decoded = json_decode($json, true);

		$this->assertEquals('/errors/not-found', $decoded['type']);
		$this->assertEquals('Not Found', $decoded['title']);
		$this->assertEquals(404, $decoded['status']);
		$this->assertEquals('User not found', $decoded['detail']);
		$this->assertEquals(123, $decoded['user_id']);
		$this->assertArrayNotHasKey('instance', $decoded);
	}

	/**
	 * Test toArray method.
	 */
	public function testToArray(): void
	{
		$problem = new ProblemDetails(
			type: '/errors/auth',
			title: 'Authentication Required',
			status: 401,
			instance: '/api/protected',
			extensions: ['realm' => 'api']
		);

		$array = $problem->toArray();

		$this->assertIsArray($array);
		$this->assertEquals('/errors/auth', $array['type']);
		$this->assertEquals('Authentication Required', $array['title']);
		$this->assertEquals(401, $array['status']);
		$this->assertEquals('/api/protected', $array['instance']);
		$this->assertEquals('api', $array['realm']);
		$this->assertArrayNotHasKey('detail', $array);
	}

	/**
	 * Test creating from array.
	 */
	public function testFromArray(): void
	{
		$data = [
			'type' => '/errors/test',
			'title' => 'Test Error',
			'status' => 400,
			'detail' => 'Test detail',
			'instance' => '/test',
			'custom_field' => 'custom value'
		];

		$problem = ProblemDetails::fromArray($data);

		$this->assertEquals('/errors/test', $problem->getType());
		$this->assertEquals('Test Error', $problem->getTitle());
		$this->assertEquals(400, $problem->getStatus());
		$this->assertEquals('Test detail', $problem->getDetail());
		$this->assertEquals('/test', $problem->getInstance());
		$this->assertEquals('custom value', $problem->getExtension('custom_field'));
	}

	/**
	 * Test validation for empty type.
	 */
	public function testValidationEmptyType(): void
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('Problem type cannot be empty');

		new ProblemDetails(
			type: '',
			title: 'Error',
			status: 400
		);
	}

	/**
	 * Test validation for empty title.
	 */
	public function testValidationEmptyTitle(): void
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('Problem title cannot be empty');

		new ProblemDetails(
			type: '/errors/test',
			title: '',
			status: 400
		);
	}

	/**
	 * Test validation for invalid status code.
	 */
	public function testValidationInvalidStatus(): void
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('Problem status must be a 4xx or 5xx HTTP status code');

		new ProblemDetails(
			type: '/errors/test',
			title: 'Error',
			status: 200
		);
	}

	/**
	 * Test validation for invalid extension field name.
	 */
	public function testValidationInvalidExtensionName(): void
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('Invalid extension field name: 123-invalid');

		new ProblemDetails(
			type: '/errors/test',
			title: 'Error',
			status: 400,
			extensions: ['123-invalid' => 'value']
		);
	}

	/**
	 * Test validation for reserved extension field name.
	 */
	public function testValidationReservedExtensionName(): void
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('Reserved field name cannot be used as extension: type');

		new ProblemDetails(
			type: '/errors/test',
			title: 'Error',
			status: 400,
			extensions: ['type' => 'value']
		);
	}

	/**
	 * Test ProblemType enum integration.
	 */
	public function testProblemTypeEnumIntegration(): void
	{
		$problem = ProblemType::VALIDATION_ERROR->toProblemDetails(
			detail: 'Invalid input',
			extensions: ['errors' => ['field' => 'error']]
		);

		$this->assertEquals('/errors/validation', $problem->getType());
		$this->assertEquals('Validation Failed', $problem->getTitle());
		$this->assertEquals(400, $problem->getStatus());
		$this->assertEquals('Invalid input', $problem->getDetail());
		$this->assertEquals(['field' => 'error'], $problem->getExtension('errors'));
	}

	/**
	 * Test ProblemType recommended status codes.
	 */
	public function testProblemTypeStatusCodes(): void
	{
		$this->assertEquals(400, ProblemType::VALIDATION_ERROR->getRecommendedStatus());
		$this->assertEquals(401, ProblemType::AUTHENTICATION_REQUIRED->getRecommendedStatus());
		$this->assertEquals(403, ProblemType::PERMISSION_DENIED->getRecommendedStatus());
		$this->assertEquals(404, ProblemType::NOT_FOUND->getRecommendedStatus());
		$this->assertEquals(429, ProblemType::RATE_LIMIT_EXCEEDED->getRecommendedStatus());
		$this->assertEquals(500, ProblemType::INTERNAL_ERROR->getRecommendedStatus());
		$this->assertEquals(503, ProblemType::SERVICE_UNAVAILABLE->getRecommendedStatus());
	}

	/**
	 * Test ProblemType default titles.
	 */
	public function testProblemTypeDefaultTitles(): void
	{
		$this->assertEquals('Validation Failed', ProblemType::VALIDATION_ERROR->getDefaultTitle());
		$this->assertEquals('Resource Not Found', ProblemType::NOT_FOUND->getDefaultTitle());
		$this->assertEquals('Authentication Required', ProblemType::AUTHENTICATION_REQUIRED->getDefaultTitle());
		$this->assertEquals('Permission Denied', ProblemType::PERMISSION_DENIED->getDefaultTitle());
		$this->assertEquals('Rate Limit Exceeded', ProblemType::RATE_LIMIT_EXCEEDED->getDefaultTitle());
		$this->assertEquals('Internal Server Error', ProblemType::INTERNAL_ERROR->getDefaultTitle());
	}

	/**
	 * Test complex nested extensions.
	 */
	public function testComplexExtensions(): void
	{
		$problem = new ProblemDetails(
			type: '/errors/complex',
			title: 'Complex Error',
			status: 400,
			extensions: [
				'errors' => [
					'user' => [
						'email' => 'Invalid format',
						'name' => 'Too short'
					],
					'address' => [
						'zip' => 'Invalid code'
					]
				],
				'metadata' => [
					'request_id' => 'abc123',
					'timestamp' => 1234567890
				]
			]
		);

		$array = $problem->toArray();
		$this->assertArrayHasKey('errors', $array);
		$this->assertArrayHasKey('metadata', $array);
		$this->assertEquals('Invalid format', $array['errors']['user']['email']);
		$this->assertEquals('abc123', $array['metadata']['request_id']);
	}

	/**
	 * Test that null extension returns null.
	 */
	public function testGetNonExistentExtension(): void
	{
		$problem = new ProblemDetails(
			type: '/errors/test',
			title: 'Test',
			status: 400
		);

		$this->assertNull($problem->getExtension('non_existent'));
	}
}