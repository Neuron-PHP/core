<?php

namespace Tests\Core\H;

use Neuron\Core\H\Error;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the Error constants class.
 *
 * Verifies that all POSIX error constants are defined with correct values.
 * This class provides a PHP implementation of the error.h C header.
 */
class ErrorTest extends TestCase
{
	public function testClassExists(): void
	{
		$this->assertTrue(class_exists(Error::class));
	}

	/**
	 * Data provider for common error constants.
	 *
	 * @return array<string, array{string, int}>
	 */
	public static function commonErrorConstantsProvider(): array
	{
		return [
			'EPERM' => ['EPERM', 1],
			'ENOENT' => ['ENOENT', 2],
			'ESRCH' => ['ESRCH', 3],
			'EINTR' => ['EINTR', 4],
			'EIO' => ['EIO', 5],
			'EACCES' => ['EACCES', 13],
			'EEXIST' => ['EEXIST', 17],
			'ENODEV' => ['ENODEV', 19],
			'ENOTDIR' => ['ENOTDIR', 20],
			'EISDIR' => ['EISDIR', 21],
			'EINVAL' => ['EINVAL', 22],
			'ENFILE' => ['ENFILE', 23],
			'EMFILE' => ['EMFILE', 24],
		];
	}

	/**
	 * @dataProvider commonErrorConstantsProvider
	 */
	public function testCommonErrorConstants(string $constantName, int $expectedValue): void
	{
		$fullConstant = Error::class . '::' . $constantName;

		$this->assertTrue(
			defined($fullConstant),
			"Constant {$constantName} is not defined"
		);

		$this->assertEquals(
			$expectedValue,
			constant($fullConstant),
			"Constant {$constantName} has incorrect value"
		);
	}

	public function testOperationNotPermitted(): void
	{
		$this->assertEquals(1, Error::EPERM);
	}

	public function testNoSuchFileOrDirectory(): void
	{
		$this->assertEquals(2, Error::ENOENT);
	}

	public function testPermissionDenied(): void
	{
		$this->assertEquals(13, Error::EACCES);
	}

	public function testFileExists(): void
	{
		$this->assertEquals(17, Error::EEXIST);
	}

	public function testInvalidArgument(): void
	{
		$this->assertEquals(22, Error::EINVAL);
	}

	public function testNoSpaceLeftOnDevice(): void
	{
		$this->assertEquals(28, Error::ENOSPC);
	}

	public function testReadOnlyFileSystem(): void
	{
		$this->assertEquals(30, Error::EROFS);
	}

	public function testBrokenPipe(): void
	{
		$this->assertEquals(32, Error::EPIPE);
	}

	public function testDirectoryNotEmpty(): void
	{
		$this->assertEquals(39, Error::ENOTEMPTY);
	}

	/**
	 * Data provider for network error constants.
	 *
	 * @return array<string, array{string, int}>
	 */
	public static function networkErrorConstantsProvider(): array
	{
		return [
			'EADDRINUSE' => ['EADDRINUSE', 98],
			'EADDRNOTAVAIL' => ['EADDRNOTAVAIL', 99],
			'ENETDOWN' => ['ENETDOWN', 100],
			'ENETUNREACH' => ['ENETUNREACH', 101],
			'ECONNABORTED' => ['ECONNABORTED', 103],
			'ECONNRESET' => ['ECONNRESET', 104],
			'ENOTCONN' => ['ENOTCONN', 107],
			'ETIMEDOUT' => ['ETIMEDOUT', 110],
			'ECONNREFUSED' => ['ECONNREFUSED', 111],
			'EHOSTDOWN' => ['EHOSTDOWN', 112],
			'EHOSTUNREACH' => ['EHOSTUNREACH', 113],
		];
	}

	/**
	 * @dataProvider networkErrorConstantsProvider
	 */
	public function testNetworkErrorConstants(string $constantName, int $expectedValue): void
	{
		$this->assertEquals($expectedValue, constant(Error::class . '::' . $constantName));
	}

	public function testWouldBlockEqualsAgain(): void
	{
		// EWOULDBLOCK and EAGAIN should have the same value
		$this->assertEquals(Error::EAGAIN, Error::EWOULDBLOCK);
		$this->assertEquals(11, Error::EWOULDBLOCK);
	}

	public function testDeadlockConstants(): void
	{
		// Both deadlock constants should exist
		$this->assertEquals(35, Error::EDEADLK);
		$this->assertEquals(58, Error::EDEADLOCK);
	}

	public function testAllConstantsAreIntegers(): void
	{
		$reflection = new \ReflectionClass(Error::class);
		$constants = $reflection->getConstants();

		foreach ($constants as $name => $value) {
			$this->assertIsInt(
				$value,
				"Constant {$name} should be an integer, got " . gettype($value)
			);
		}
	}

	public function testConstantsAreNotNegative(): void
	{
		$reflection = new \ReflectionClass(Error::class);
		$constants = $reflection->getConstants();

		foreach ($constants as $name => $value) {
			$this->assertGreaterThanOrEqual(
				0,
				$value,
				"Constant {$name} should not be negative"
			);
		}
	}

	public function testRequiredConstantsExist(): void
	{
		$requiredConstants = [
			'EPERM', 'ENOENT', 'ESRCH', 'EINTR', 'EIO', 'ENXIO',
			'E2BIG', 'ENOEXEC', 'EBADF', 'ECHILD', 'EAGAIN', 'ENOMEM',
			'EACCES', 'EFAULT', 'EBUSY', 'EEXIST', 'ENODEV', 'ENOTDIR',
			'EISDIR', 'EINVAL', 'ENFILE', 'EMFILE', 'ENOSPC', 'EROFS',
			'EPIPE', 'ENOTEMPTY', 'EADDRINUSE', 'ECONNREFUSED', 'ETIMEDOUT'
		];

		foreach ($requiredConstants as $constant) {
			$this->assertTrue(
				defined(Error::class . '::' . $constant),
				"Required constant {$constant} is missing"
			);
		}
	}

	public function testConstantCount(): void
	{
		$reflection = new \ReflectionClass(Error::class);
		$constants = $reflection->getConstants();

		// Error.h implementation should have many constants
		$this->assertGreaterThan(100, count($constants));
	}

	public function testChildProcessConstant(): void
	{
		// ECHILD should be 0 (as defined in the source)
		$this->assertEquals(0, Error::ECHILD);
	}

	public function testRestartSystemCallConstants(): void
	{
		$this->assertEquals(512, Error::ERESTARTSYS);
		$this->assertEquals(513, Error::ERESTARTNOINTR);
	}

	/**
	 * Test that specific error codes match their expected semantics.
	 */
	public function testErrorCodeSemantics(): void
	{
		// File/Directory errors
		$this->assertEquals(2, Error::ENOENT);      // No such file or directory
		$this->assertEquals(20, Error::ENOTDIR);    // Not a directory
		$this->assertEquals(21, Error::EISDIR);     // Is a directory
		$this->assertEquals(39, Error::ENOTEMPTY);  // Directory not empty

		// Permission errors
		$this->assertEquals(1, Error::EPERM);       // Operation not permitted
		$this->assertEquals(13, Error::EACCES);     // Permission denied

		// Resource errors
		$this->assertEquals(12, Error::ENOMEM);     // Out of memory
		$this->assertEquals(28, Error::ENOSPC);     // No space left on device
		$this->assertEquals(24, Error::EMFILE);     // Too many open files

		// Network errors
		$this->assertEquals(98, Error::EADDRINUSE);    // Address already in use
		$this->assertEquals(111, Error::ECONNREFUSED); // Connection refused
		$this->assertEquals(110, Error::ETIMEDOUT);    // Connection timed out
	}
}
