<?php

namespace Tests\Core\Exceptions;

use Neuron\Core\Exceptions\BadRequestMethod;
use Neuron\Core\Exceptions\Base;
use Neuron\Core\Exceptions\CommandNotFound;
use Neuron\Core\Exceptions\EmptyActionParameter;
use Neuron\Core\Exceptions\MapNotFound;
use Neuron\Core\Exceptions\MissingMethod;
use Neuron\Core\Exceptions\NotFound;
use Neuron\Core\Exceptions\PropertyNotFound;
use Neuron\Core\Exceptions\RouteParam;
use Neuron\Core\Exceptions\Validation;
use PHPUnit\Framework\TestCase;

/**
 * Comprehensive tests for all Core exception classes.
 *
 * Tests exception inheritance, instantiation, message handling,
 * and code handling for all Neuron Core exceptions.
 */
class ExceptionsTest extends TestCase
{
	/**
	 * Data provider for simple exceptions (no required constructor params).
	 *
	 * @return array<string, array{class-string<\Exception>}>
	 */
	public static function simpleExceptionClassProvider(): array
	{
		return [
			'Base' => [Base::class],
			'BadRequestMethod' => [BadRequestMethod::class],
			'CommandNotFound' => [CommandNotFound::class],
			'EmptyActionParameter' => [EmptyActionParameter::class],
			'MissingMethod' => [MissingMethod::class],
			'NotFound' => [NotFound::class],
		];
	}

	/**
	 * @dataProvider simpleExceptionClassProvider
	 * @param class-string<\Exception> $exceptionClass
	 */
	public function testSimpleExceptionCanBeInstantiated(string $exceptionClass): void
	{
		$exception = new $exceptionClass();

		$this->assertInstanceOf(\Exception::class, $exception);
		$this->assertInstanceOf($exceptionClass, $exception);
	}

	/**
	 * @dataProvider simpleExceptionClassProvider
	 * @param class-string<\Exception> $exceptionClass
	 */
	public function testSimpleExceptionWithMessage(string $exceptionClass): void
	{
		$message = 'Test exception message';
		$exception = new $exceptionClass($message);

		$this->assertEquals($message, $exception->getMessage());
	}

	/**
	 * @dataProvider simpleExceptionClassProvider
	 * @param class-string<\Exception> $exceptionClass
	 */
	public function testSimpleExceptionWithMessageAndCode(string $exceptionClass): void
	{
		$message = 'Test exception message';
		$code = 42;
		$exception = new $exceptionClass($message, $code);

		$this->assertEquals($message, $exception->getMessage());
		$this->assertEquals($code, $exception->getCode());
	}

	public function testBaseExceptionExtendsException(): void
	{
		$exception = new Base();
		$this->assertInstanceOf(\Exception::class, $exception);
	}

	public function testAllExceptionsExtendBase(): void
	{
		$exceptions = [
			new BadRequestMethod(),
			new CommandNotFound(),
			new EmptyActionParameter(),
			new MapNotFound('test'),
			new MissingMethod(),
			new NotFound(),
			new PropertyNotFound('test'),
			new RouteParam('test'),
			new Validation('test', []),
		];

		foreach ($exceptions as $exception) {
			$this->assertInstanceOf(
				Base::class,
				$exception,
				get_class($exception) . " does not extend Base"
			);
		}
	}

	// Specific tests for MapNotFound
	public function testMapNotFoundException(): void
	{
		$exception = new MapNotFound('UserMap');

		$this->assertStringContainsString('UserMap', $exception->getMessage());
		$this->assertStringContainsString('Missing map to:', $exception->getMessage());
		$this->assertInstanceOf(NotFound::class, $exception);
	}

	public function testMapNotFoundCanBeThrown(): void
	{
		$this->expectException(MapNotFound::class);
		$this->expectExceptionMessage('Missing map to: TestMap');

		throw new MapNotFound('TestMap');
	}

	// Specific tests for PropertyNotFound
	public function testPropertyNotFoundException(): void
	{
		$exception = new PropertyNotFound('username');

		$this->assertStringContainsString('username', $exception->getMessage());
		$this->assertStringContainsString('Missing map to:', $exception->getMessage());
		$this->assertInstanceOf(NotFound::class, $exception);
	}

	public function testPropertyNotFoundCanBeThrown(): void
	{
		$this->expectException(PropertyNotFound::class);
		$this->expectExceptionMessage('Missing map to: email');

		throw new PropertyNotFound('email');
	}

	// Specific tests for RouteParam
	public function testRouteParamException(): void
	{
		$exception = new RouteParam('Route parameter "id" is invalid');

		$this->assertStringContainsString('id', $exception->getMessage());
		$this->assertStringContainsString('invalid', $exception->getMessage());
	}

	public function testRouteParamWithNumericConstraint(): void
	{
		$exception = new RouteParam('Parameter must be numeric');

		$this->assertStringContainsString('numeric', $exception->getMessage());
	}

	public function testRouteParamCanBeThrown(): void
	{
		$this->expectException(RouteParam::class);
		$this->expectExceptionMessage('Invalid parameter');

		throw new RouteParam('Invalid parameter');
	}

	// Specific tests for Validation
	public function testValidationException(): void
	{
		$errors = [
			'email' => 'Invalid email format',
			'password' => 'Password too short'
		];

		$exception = new Validation('User Registration', $errors);

		$this->assertStringContainsString('User Registration', $exception->getMessage());
		$this->assertEquals($errors, $exception->errors);
		$this->assertArrayHasKey('email', $exception->errors);
		$this->assertArrayHasKey('password', $exception->errors);
		$this->assertEquals('Invalid email format', $exception->errors['email']);
	}

	public function testValidationExceptionWithEmptyErrors(): void
	{
		$exception = new Validation('Test', []);

		$this->assertEmpty($exception->errors);
		$this->assertIsArray($exception->errors);
	}

	public function testValidationExceptionWithMultipleErrors(): void
	{
		$errors = [
			'field1' => 'Error 1',
			'field2' => 'Error 2',
			'field3' => 'Error 3'
		];

		$exception = new Validation('Multi-field validation', $errors);

		$this->assertCount(3, $exception->errors);
		foreach ($errors as $field => $message) {
			$this->assertEquals($message, $exception->errors[$field]);
		}
	}

	public function testValidationCanBeThrown(): void
	{
		$this->expectException(Validation::class);
		$this->expectExceptionMessage('Validation failed for Form');

		throw new Validation('Form', ['field' => 'error']);
	}

	// Other specific exception tests
	public function testBadRequestMethodException(): void
	{
		$exception = new BadRequestMethod('GET is not allowed');

		$this->assertStringContainsString('GET', $exception->getMessage());
		$this->assertInstanceOf(\Exception::class, $exception);
	}

	public function testCommandNotFoundException(): void
	{
		$exception = new CommandNotFound('Command "test" not found');

		$this->assertStringContainsString('test', $exception->getMessage());
	}

	public function testEmptyActionParameterException(): void
	{
		$exception = new EmptyActionParameter('Action parameter is required');

		$this->assertStringContainsString('required', $exception->getMessage());
	}

	public function testMissingMethodException(): void
	{
		$exception = new MissingMethod('Method "process" is missing');

		$this->assertStringContainsString('process', $exception->getMessage());
	}

	public function testNotFoundException(): void
	{
		$exception = new NotFound('Resource not found');

		$this->assertStringContainsString('Resource', $exception->getMessage());
	}

	public function testExceptionWithPreviousException(): void
	{
		$previous = new \Exception('Previous exception');
		$exception = new Base('Current exception', 0, $previous);

		$this->assertSame($previous, $exception->getPrevious());
		$this->assertEquals('Previous exception', $exception->getPrevious()->getMessage());
	}

	public function testExceptionToString(): void
	{
		$exception = new Base('Test message', 123);
		$string = (string) $exception;

		$this->assertStringContainsString('Test message', $string);
		$this->assertStringContainsString('Base', $string);
	}

	public function testExceptionsCanBeCaught(): void
	{
		$caught = false;

		try {
			throw new Base('Test');
		} catch (\Exception $e) {
			$caught = true;
			$this->assertInstanceOf(Base::class, $e);
		}

		$this->assertTrue($caught);
	}

	public function testMapNotFoundExtendsNotFound(): void
	{
		$exception = new MapNotFound('test');
		$this->assertInstanceOf(NotFound::class, $exception);
		$this->assertInstanceOf(Base::class, $exception);
	}

	public function testPropertyNotFoundExtendsNotFound(): void
	{
		$exception = new PropertyNotFound('test');
		$this->assertInstanceOf(NotFound::class, $exception);
		$this->assertInstanceOf(Base::class, $exception);
	}
}
