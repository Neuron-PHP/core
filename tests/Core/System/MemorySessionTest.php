<?php

namespace Tests\Core\System;

use Neuron\Core\System\ISession;
use Neuron\Core\System\MemorySession;
use PHPUnit\Framework\TestCase;

/**
 * Tests for MemorySession implementation
 */
class MemorySessionTest extends TestCase
{
	private MemorySession $session;

	protected function setUp(): void
	{
		$this->session = new MemorySession();
	}

	public function testImplementsInterface(): void
	{
		$this->assertInstanceOf( ISession::class, $this->session );
	}

	public function testConstructorWithCustomId(): void
	{
		$session = new MemorySession( 'custom-session-id' );
		$session->start();

		$this->assertEquals( 'custom-session-id', $session->getId() );
	}

	public function testConstructorGeneratesRandomId(): void
	{
		$session1 = new MemorySession();
		$session1->start();

		$session2 = new MemorySession();
		$session2->start();

		$this->assertNotEquals( $session1->getId(), $session2->getId() );
	}

	public function testStartInitializesSession(): void
	{
		$this->assertFalse( $this->session->isStarted() );

		$this->session->start();

		$this->assertTrue( $this->session->isStarted() );
	}

	public function testStartIsIdempotent(): void
	{
		$this->session->start();
		$id1 = $this->session->getId();

		$this->session->start(); // Call again

		$this->assertEquals( $id1, $this->session->getId() );
	}

	public function testSetAndGet(): void
	{
		$this->session->start();

		$this->session->set( 'user_id', 123 );

		$this->assertEquals( 123, $this->session->get( 'user_id' ) );
	}

	public function testGetWithDefault(): void
	{
		$this->session->start();

		$value = $this->session->get( 'non_existent', 'default_value' );

		$this->assertEquals( 'default_value', $value );
	}

	public function testHas(): void
	{
		$this->session->start();

		$this->assertFalse( $this->session->has( 'key' ) );

		$this->session->set( 'key', 'value' );

		$this->assertTrue( $this->session->has( 'key' ) );
	}

	public function testRemove(): void
	{
		$this->session->start();
		$this->session->set( 'key', 'value' );

		$this->assertTrue( $this->session->has( 'key' ) );

		$this->session->remove( 'key' );

		$this->assertFalse( $this->session->has( 'key' ) );
	}

	public function testClear(): void
	{
		$this->session->start();
		$this->session->set( 'key1', 'value1' );
		$this->session->set( 'key2', 'value2' );

		$this->session->clear();

		$this->assertFalse( $this->session->has( 'key1' ) );
		$this->assertFalse( $this->session->has( 'key2' ) );
	}

	public function testAll(): void
	{
		$this->session->start();
		$this->session->set( 'key1', 'value1' );
		$this->session->set( 'key2', 'value2' );

		$all = $this->session->all();

		$this->assertArrayHasKey( 'key1', $all );
		$this->assertArrayHasKey( 'key2', $all );
		$this->assertEquals( 'value1', $all['key1'] );
		$this->assertEquals( 'value2', $all['key2'] );
	}

	public function testRegenerate(): void
	{
		$this->session->start();
		$oldId = $this->session->getId();

		$result = $this->session->regenerate();

		$this->assertTrue( $result );
		$this->assertNotEquals( $oldId, $this->session->getId() );
	}

	public function testRegeneratePreservesData(): void
	{
		$this->session->start();
		$this->session->set( 'user_id', 123 );

		$this->session->regenerate();

		$this->assertEquals( 123, $this->session->get( 'user_id' ) );
	}

	public function testDestroy(): void
	{
		$this->session->start();
		$this->session->set( 'key', 'value' );

		$result = $this->session->destroy();

		$this->assertTrue( $result );
		$this->assertFalse( $this->session->isStarted() );
	}

	public function testDestroyResetsData(): void
	{
		$this->session->start();
		$this->session->set( 'key', 'value' );

		$this->session->destroy();
		$this->session->start();

		$this->assertNull( $this->session->get( 'key' ) );
	}

	public function testFlashSetsFlashMessage(): void
	{
		$this->session->start();

		$this->session->flash( 'success', 'Operation successful!' );

		// Flash should not be immediately available
		$this->assertNull( $this->session->getFlash( 'success' ) );
	}

	public function testFlashIsAvailableAfterRestart(): void
	{
		$this->session->start();
		$this->session->flash( 'success', 'Operation successful!' );

		// Simulate next request by destroying and restarting
		$data = $this->session->all();
		$id = $this->session->getId();
		$this->session->destroy();

		// Manually restore data to simulate persistence
		$newSession = new MemorySession( $id );
		$newSession->start();
		foreach( $data as $key => $value )
		{
			$newSession->set( $key, $value );
		}

		// Manually age flash since we restored data after start
		// In a real session, this would happen automatically on start()
		$all = $newSession->all();
		if( isset( $all['_flash_new'] ) )
		{
			$newSession->set( '_flash', $all['_flash_new'] );
			$newSession->remove( '_flash_new' );
		}

		// Now flash should be available
		$this->assertEquals( 'Operation successful!', $newSession->getFlash( 'success' ) );
	}

	public function testGetFlashRemovesAfterRetrieval(): void
	{
		$this->session->start();
		$this->session->flash( 'message', 'Test message' );

		// Manually move flash from _flash_new to _flash
		$all = $this->session->all();
		if( isset( $all['_flash_new'] ) )
		{
			$this->session->set( '_flash', $all['_flash_new'] );
			$this->session->remove( '_flash_new' );
		}

		// First retrieval should return value
		$this->assertEquals( 'Test message', $this->session->getFlash( 'message' ) );

		// Second retrieval should return default
		$this->assertNull( $this->session->getFlash( 'message' ) );
	}

	public function testGetFlashWithDefault(): void
	{
		$this->session->start();

		$value = $this->session->getFlash( 'non_existent', 'default' );

		$this->assertEquals( 'default', $value );
	}

	public function testMultipleFlashMessages(): void
	{
		$this->session->start();
		$this->session->flash( 'success', 'Success message' );
		$this->session->flash( 'error', 'Error message' );
		$this->session->flash( 'warning', 'Warning message' );

		$all = $this->session->all();
		$this->assertArrayHasKey( '_flash_new', $all );
		$this->assertCount( 3, $all['_flash_new'] );
	}

	public function testSessionAutoStartsWhenNeeded(): void
	{
		// Session should auto-start when accessing data
		$this->assertFalse( $this->session->isStarted() );

		$this->session->set( 'key', 'value' );

		$this->assertTrue( $this->session->isStarted() );
	}

	public function testGetIdAutoStarts(): void
	{
		$this->assertFalse( $this->session->isStarted() );

		$id = $this->session->getId();

		$this->assertTrue( $this->session->isStarted() );
		$this->assertIsString( $id );
	}

	public function testVariousDataTypes(): void
	{
		$this->session->start();

		$this->session->set( 'int', 123 );
		$this->session->set( 'string', 'test' );
		$this->session->set( 'array', [1, 2, 3] );
		$this->session->set( 'bool', true );
		$this->session->set( 'null', null );

		$this->assertEquals( 123, $this->session->get( 'int' ) );
		$this->assertEquals( 'test', $this->session->get( 'string' ) );
		$this->assertEquals( [1, 2, 3], $this->session->get( 'array' ) );
		$this->assertTrue( $this->session->get( 'bool' ) );
		$this->assertNull( $this->session->get( 'null' ) );
	}

	public function testComplexSessionFlow(): void
	{
		// Start session and set data
		$this->session->start();
		$this->session->set( 'user_id', 123 );
		$this->session->set( 'username', 'testuser' );
		$this->session->flash( 'welcome', 'Welcome back!' );

		$this->assertTrue( $this->session->has( 'user_id' ) );
		$this->assertEquals( 'testuser', $this->session->get( 'username' ) );

		// Regenerate session ID
		$oldId = $this->session->getId();
		$this->session->regenerate();
		$newId = $this->session->getId();

		$this->assertNotEquals( $oldId, $newId );
		$this->assertEquals( 123, $this->session->get( 'user_id' ) );

		// Remove specific key
		$this->session->remove( 'username' );
		$this->assertFalse( $this->session->has( 'username' ) );
		$this->assertTrue( $this->session->has( 'user_id' ) );

		// Destroy everything
		$this->session->destroy();
		$this->assertFalse( $this->session->isStarted() );
	}
}
