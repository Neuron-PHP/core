<?php

namespace Tests\Core\Exceptions;

use Neuron\Core\Exceptions\Unauthorized;
use PHPUnit\Framework\TestCase;

class UnauthorizedTest extends TestCase
{
	public function testDefaultConstructor(): void
	{
		$exception = new Unauthorized();

		$this->assertEquals( 'Authentication required', $exception->getMessage() );
		$this->assertEquals( 401, $exception->getCode() );
		$this->assertNull( $exception->getRealm() );
		$this->assertNull( $exception->getPrevious() );
	}

	public function testConstructorWithMessage(): void
	{
		$exception = new Unauthorized( 'Invalid API key' );

		$this->assertEquals( 'Invalid API key', $exception->getMessage() );
		$this->assertEquals( 401, $exception->getCode() );
		$this->assertNull( $exception->getRealm() );
	}

	public function testConstructorWithRealm(): void
	{
		$exception = new Unauthorized( 'Authentication failed', 'Admin Panel' );

		$this->assertEquals( 'Authentication failed', $exception->getMessage() );
		$this->assertEquals( 'Admin Panel', $exception->getRealm() );
		$this->assertEquals( 401, $exception->getCode() );
	}

	public function testConstructorWithCustomCode(): void
	{
		$exception = new Unauthorized( 'Custom error', null, 999 );

		$this->assertEquals( 'Custom error', $exception->getMessage() );
		$this->assertNull( $exception->getRealm() );
		$this->assertEquals( 999, $exception->getCode() );
	}

	public function testConstructorWithPreviousException(): void
	{
		$previous = new \Exception( 'Previous error' );
		$exception = new Unauthorized( 'Auth error', null, 401, $previous );

		$this->assertEquals( 'Auth error', $exception->getMessage() );
		$this->assertSame( $previous, $exception->getPrevious() );
	}

	public function testConstructorWithAllParameters(): void
	{
		$previous = new \RuntimeException( 'Runtime error' );
		$exception = new Unauthorized( 'Full auth error', 'Protected Area', 401, $previous );

		$this->assertEquals( 'Full auth error', $exception->getMessage() );
		$this->assertEquals( 'Protected Area', $exception->getRealm() );
		$this->assertEquals( 401, $exception->getCode() );
		$this->assertSame( $previous, $exception->getPrevious() );
	}

	public function testVariousRealms(): void
	{
		$realms = [
			'Basic Auth',
			'Bearer Token',
			'API Key Required',
			'OAuth 2.0',
			'Session Expired'
		];

		foreach( $realms as $realm )
		{
			$exception = new Unauthorized( 'Test message', $realm );
			$this->assertEquals( $realm, $exception->getRealm() );
		}
	}

	public function testExceptionThrowable(): void
	{
		$this->expectException( Unauthorized::class );
		$this->expectExceptionMessage( 'You shall not pass' );
		$this->expectExceptionCode( 401 );

		throw new Unauthorized( 'You shall not pass' );
	}

	public function testExceptionWithRealmThrowable(): void
	{
		$this->expectException( Unauthorized::class );

		try
		{
			throw new Unauthorized( 'Access denied', 'Secure Zone' );
		}
		catch( Unauthorized $e )
		{
			$this->assertEquals( 'Access denied', $e->getMessage() );
			$this->assertEquals( 'Secure Zone', $e->getRealm() );
			$this->assertEquals( 401, $e->getCode() );
			throw $e;
		}
	}

	public function testInheritanceFromException(): void
	{
		$exception = new Unauthorized();

		$this->assertInstanceOf( \Exception::class, $exception );
		$this->assertInstanceOf( \Throwable::class, $exception );
	}

	public function testGetRealmReturnType(): void
	{
		$exception1 = new Unauthorized( 'Test', 'MyRealm' );
		$this->assertIsString( $exception1->getRealm() );

		$exception2 = new Unauthorized( 'Test' );
		$this->assertNull( $exception2->getRealm() );
	}
}