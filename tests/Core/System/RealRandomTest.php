<?php

namespace Tests\Core\System;

use Neuron\Core\System\IRandom;
use Neuron\Core\System\RealRandom;
use PHPUnit\Framework\TestCase;

/**
 * Tests for RealRandom implementation
 */
class RealRandomTest extends TestCase
{
	private IRandom $random;

	protected function setUp(): void
	{
		$this->random = new RealRandom();
	}

	public function testImplementsInterface(): void
	{
		$this->assertInstanceOf( IRandom::class, $this->random );
	}

	public function testBytesReturnsCorrectLength(): void
	{
		$bytes = $this->random->bytes( 16 );

		$this->assertIsString( $bytes );
		$this->assertEquals( 16, strlen( $bytes ) );
	}

	public function testBytesProducesDifferentResults(): void
	{
		$bytes1 = $this->random->bytes( 16 );
		$bytes2 = $this->random->bytes( 16 );

		$this->assertNotEquals( $bytes1, $bytes2 );
	}

	public function testIntReturnsIntegerInRange(): void
	{
		$int = $this->random->int( 1, 100 );

		$this->assertIsInt( $int );
		$this->assertGreaterThanOrEqual( 1, $int );
		$this->assertLessThanOrEqual( 100, $int );
	}

	public function testIntProducesDifferentResults(): void
	{
		$results = [];
		for( $i = 0; $i < 10; $i++ )
		{
			$results[] = $this->random->int( 1, 1000 );
		}

		// At least some values should be different
		$uniqueValues = array_unique( $results );
		$this->assertGreaterThan( 1, count( $uniqueValues ) );
	}

	public function testUniqueIdReturnsString(): void
	{
		$id = $this->random->uniqueId();

		$this->assertIsString( $id );
		$this->assertGreaterThan( 0, strlen( $id ) );
	}

	public function testUniqueIdWithPrefix(): void
	{
		$id = $this->random->uniqueId( 'test_' );

		$this->assertStringStartsWith( 'test_', $id );
	}

	public function testUniqueIdProducesDifferentResults(): void
	{
		$id1 = $this->random->uniqueId();
		$id2 = $this->random->uniqueId();

		$this->assertNotEquals( $id1, $id2 );
	}

	public function testStringHexReturnsCorrectLength(): void
	{
		$string = $this->random->string( 32, 'hex' );

		$this->assertIsString( $string );
		$this->assertEquals( 32, strlen( $string ) );
		$this->assertMatchesRegularExpression( '/^[0-9a-f]+$/', $string );
	}

	public function testStringBase64ReturnsCorrectLength(): void
	{
		$string = $this->random->string( 32, 'base64' );

		$this->assertIsString( $string );
		$this->assertEquals( 32, strlen( $string ) );
	}

	public function testStringAlphanumericReturnsCorrectLength(): void
	{
		$string = $this->random->string( 32, 'alphanumeric' );

		$this->assertIsString( $string );
		$this->assertEquals( 32, strlen( $string ) );
		$this->assertMatchesRegularExpression( '/^[a-zA-Z0-9]+$/', $string );
	}

	public function testStringAlphaReturnsCorrectLength(): void
	{
		$string = $this->random->string( 32, 'alpha' );

		$this->assertIsString( $string );
		$this->assertEquals( 32, strlen( $string ) );
		$this->assertMatchesRegularExpression( '/^[a-zA-Z]+$/', $string );
	}

	public function testStringNumericReturnsCorrectLength(): void
	{
		$string = $this->random->string( 32, 'numeric' );

		$this->assertIsString( $string );
		$this->assertEquals( 32, strlen( $string ) );
		$this->assertMatchesRegularExpression( '/^[0-9]+$/', $string );
	}

	public function testStringProducesDifferentResults(): void
	{
		$string1 = $this->random->string( 32, 'hex' );
		$string2 = $this->random->string( 32, 'hex' );

		$this->assertNotEquals( $string1, $string2 );
	}

	public function testStringWithInvalidCharsetThrowsException(): void
	{
		$this->expectException( \InvalidArgumentException::class );
		$this->expectExceptionMessage( 'Unknown charset: invalid' );

		$this->random->string( 10, 'invalid' );
	}

	public function testFloatReturnsFloatBetweenZeroAndOne(): void
	{
		$float = $this->random->float();

		$this->assertIsFloat( $float );
		$this->assertGreaterThanOrEqual( 0.0, $float );
		$this->assertLessThanOrEqual( 1.0, $float );
	}

	public function testFloatProducesDifferentResults(): void
	{
		$results = [];
		for( $i = 0; $i < 10; $i++ )
		{
			$results[] = $this->random->float();
		}

		$uniqueValues = array_unique( $results );
		$this->assertGreaterThan( 1, count( $uniqueValues ) );
	}

	public function testShuffleRandomizesArray(): void
	{
		$original = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];

		$shuffled = $this->random->shuffle( $original );

		$this->assertCount( 10, $shuffled );
		$this->assertNotEquals( $original, $shuffled ); // Very unlikely to be same order
	}

	public function testShufflePreservesElements(): void
	{
		$original = ['a', 'b', 'c', 'd', 'e'];

		$shuffled = $this->random->shuffle( $original );

		sort( $original );
		sort( $shuffled );
		$this->assertEquals( $original, $shuffled );
	}

	public function testShuffleWithEmptyArray(): void
	{
		$shuffled = $this->random->shuffle( [] );

		$this->assertEquals( [], $shuffled );
	}
}
