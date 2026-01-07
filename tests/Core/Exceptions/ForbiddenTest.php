<?php

namespace Tests\Core\Exceptions;

use Neuron\Core\Exceptions\Forbidden;
use PHPUnit\Framework\TestCase;

class ForbiddenTest extends TestCase
{
	public function testDefaultConstructor(): void
	{
		$exception = new Forbidden();

		$this->assertEquals( 'Access forbidden', $exception->getMessage() );
		$this->assertEquals( 403, $exception->getCode() );
		$this->assertNull( $exception->getResource() );
		$this->assertNull( $exception->getPermission() );
		$this->assertNull( $exception->getPrevious() );
	}

	public function testConstructorWithMessage(): void
	{
		$exception = new Forbidden( 'You do not have access' );

		$this->assertEquals( 'You do not have access', $exception->getMessage() );
		$this->assertEquals( 403, $exception->getCode() );
		$this->assertNull( $exception->getResource() );
		$this->assertNull( $exception->getPermission() );
	}

	public function testConstructorWithResource(): void
	{
		$exception = new Forbidden( 'Access denied', 'Admin Dashboard' );

		$this->assertEquals( 'Access denied', $exception->getMessage() );
		$this->assertEquals( 'Admin Dashboard', $exception->getResource() );
		$this->assertNull( $exception->getPermission() );
		$this->assertEquals( 403, $exception->getCode() );
	}

	public function testConstructorWithResourceAndPermission(): void
	{
		$exception = new Forbidden( 'Insufficient permissions', 'User Profile', 'user.edit' );

		$this->assertEquals( 'Insufficient permissions', $exception->getMessage() );
		$this->assertEquals( 'User Profile', $exception->getResource() );
		$this->assertEquals( 'user.edit', $exception->getPermission() );
		$this->assertEquals( 403, $exception->getCode() );
	}

	public function testConstructorWithCustomCode(): void
	{
		$exception = new Forbidden( 'Custom forbidden', null, null, 999 );

		$this->assertEquals( 'Custom forbidden', $exception->getMessage() );
		$this->assertNull( $exception->getResource() );
		$this->assertNull( $exception->getPermission() );
		$this->assertEquals( 999, $exception->getCode() );
	}

	public function testConstructorWithPreviousException(): void
	{
		$previous = new \Exception( 'Previous error' );
		$exception = new Forbidden( 'Forbidden error', null, null, 403, $previous );

		$this->assertEquals( 'Forbidden error', $exception->getMessage() );
		$this->assertSame( $previous, $exception->getPrevious() );
	}

	public function testConstructorWithAllParameters(): void
	{
		$previous = new \RuntimeException( 'Runtime error' );
		$exception = new Forbidden(
			'Complete forbidden error',
			'Protected Document',
			'document.delete',
			403,
			$previous
		);

		$this->assertEquals( 'Complete forbidden error', $exception->getMessage() );
		$this->assertEquals( 'Protected Document', $exception->getResource() );
		$this->assertEquals( 'document.delete', $exception->getPermission() );
		$this->assertEquals( 403, $exception->getCode() );
		$this->assertSame( $previous, $exception->getPrevious() );
	}

	public function testVariousResources(): void
	{
		$resources = [
			'User Account',
			'System Settings',
			'Database Record #123',
			'API Endpoint /admin/users',
			'Configuration File'
		];

		foreach( $resources as $resource )
		{
			$exception = new Forbidden( 'Test', $resource );
			$this->assertEquals( $resource, $exception->getResource() );
		}
	}

	public function testVariousPermissions(): void
	{
		$permissions = [
			'admin.read',
			'posts.create',
			'users.update',
			'settings.delete',
			'api.execute',
			'system.configure'
		];

		foreach( $permissions as $permission )
		{
			$exception = new Forbidden( 'Test', 'Resource', $permission );
			$this->assertEquals( $permission, $exception->getPermission() );
		}
	}

	public function testExceptionThrowable(): void
	{
		$this->expectException( Forbidden::class );
		$this->expectExceptionMessage( 'Access denied to system' );
		$this->expectExceptionCode( 403 );

		throw new Forbidden( 'Access denied to system' );
	}

	public function testExceptionWithDetailsThrowable(): void
	{
		$this->expectException( Forbidden::class );

		try
		{
			throw new Forbidden( 'Cannot delete user', 'User #42', 'users.delete' );
		}
		catch( Forbidden $e )
		{
			$this->assertEquals( 'Cannot delete user', $e->getMessage() );
			$this->assertEquals( 'User #42', $e->getResource() );
			$this->assertEquals( 'users.delete', $e->getPermission() );
			$this->assertEquals( 403, $e->getCode() );
			throw $e;
		}
	}

	public function testInheritanceFromException(): void
	{
		$exception = new Forbidden();

		$this->assertInstanceOf( \Exception::class, $exception );
		$this->assertInstanceOf( \Throwable::class, $exception );
	}

	public function testGetResourceReturnType(): void
	{
		$exception1 = new Forbidden( 'Test', 'My Resource' );
		$this->assertIsString( $exception1->getResource() );

		$exception2 = new Forbidden( 'Test' );
		$this->assertNull( $exception2->getResource() );
	}

	public function testGetPermissionReturnType(): void
	{
		$exception1 = new Forbidden( 'Test', 'Resource', 'permission.name' );
		$this->assertIsString( $exception1->getPermission() );

		$exception2 = new Forbidden( 'Test' );
		$this->assertNull( $exception2->getPermission() );
	}

	public function testResourceWithoutPermission(): void
	{
		$exception = new Forbidden( 'Forbidden', 'Secret File' );

		$this->assertEquals( 'Secret File', $exception->getResource() );
		$this->assertNull( $exception->getPermission() );
	}

	public function testPermissionWithNullResource(): void
	{
		// Test that permission can be set even with null resource
		$exception = new Forbidden( 'No permission', null, 'admin.access' );

		$this->assertNull( $exception->getResource() );
		$this->assertEquals( 'admin.access', $exception->getPermission() );
	}
}