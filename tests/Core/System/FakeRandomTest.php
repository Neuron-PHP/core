<?php

namespace Tests\Core\System;

use Neuron\Core\System\IRandom;
use Neuron\Core\System\FakeRandom;
use PHPUnit\Framework\TestCase;

/**
 * Tests for FakeRandom implementation
 */
class FakeRandomTest extends TestCase
{
	public function testImplementsInterface(): void
	{
		$random = new FakeRandom();
		$this->assertInstanceOf( IRandom::class, $random );
	}

	public function testBytesWithSequence(): void
	{
		$random = new FakeRandom();
		$random->setByteSequence( ["\x01\x02", "\x03\x04", "\x05\x06"] );

		$this->assertEquals( "\x01\x02", $random->bytes( 2 ) );
		$this->assertEquals( "\x03\x04", $random->bytes( 2 ) );
		$this->assertEquals( "\x05\x06", $random->bytes( 2 ) );
	}

	public function testBytesWithSeed(): void
	{
		$random = new FakeRandom();
		$random->setSeed( 42 );

		$bytes1 = $random->bytes( 4 );
		$bytes2 = $random->bytes( 4 );

		$this->assertIsString( $bytes1 );
		$this->assertIsString( $bytes2 );
		$this->assertEquals( 4, strlen( $bytes1 ) );
		$this->assertEquals( 4, strlen( $bytes2 ) );
	}

	public function testBytesIsPredictable(): void
	{
		$random1 = new FakeRandom();
		$random1->setSeed( 100 );

		$random2 = new FakeRandom();
		$random2->setSeed( 100 );

		$this->assertEquals( $random1->bytes( 10 ), $random2->bytes( 10 ) );
	}

	public function testIntWithSequence(): void
	{
		$random = new FakeRandom();
		$random->setIntSequence( [5, 10, 15, 20] );

		$this->assertEquals( 5, $random->int( 1, 100 ) );
		$this->assertEquals( 10, $random->int( 1, 100 ) );
		$this->assertEquals( 15, $random->int( 1, 100 ) );
		$this->assertEquals( 20, $random->int( 1, 100 ) );
	}

	public function testIntWithSeed(): void
	{
		$random = new FakeRandom();
		$random->setSeed( 42 );

		$int = $random->int( 1, 100 );

		$this->assertIsInt( $int );
		$this->assertGreaterThanOrEqual( 1, $int );
		$this->assertLessThanOrEqual( 100, $int );
	}

	public function testIntIsPredictable(): void
	{
		$random1 = new FakeRandom();
		$random1->setSeed( 100 );

		$random2 = new FakeRandom();
		$random2->setSeed( 100 );

		$this->assertEquals( $random1->int( 1, 100 ), $random2->int( 1, 100 ) );
	}

	public function testUniqueIdWithSequence(): void
	{
		$random = new FakeRandom();
		$random->setUniqueIdSequence( ['abc123', 'def456', 'ghi789'] );

		$this->assertEquals( 'abc123', $random->uniqueId() );
		$this->assertEquals( 'def456', $random->uniqueId() );
		$this->assertEquals( 'ghi789', $random->uniqueId() );
	}

	public function testUniqueIdWithPrefix(): void
	{
		$random = new FakeRandom();
		$random->setUniqueIdSequence( ['123', '456'] );

		$this->assertEquals( 'test_123', $random->uniqueId( 'test_' ) );
		$this->assertEquals( 'test_456', $random->uniqueId( 'test_' ) );
	}

	public function testUniqueIdWithSeed(): void
	{
		$random = new FakeRandom();
		$random->setSeed( 42 );

		$id1 = $random->uniqueId();
		$id2 = $random->uniqueId();

		$this->assertIsString( $id1 );
		$this->assertIsString( $id2 );
		$this->assertNotEquals( $id1, $id2 ); // Seed increments
	}

	public function testUniqueIdIsPredictable(): void
	{
		$random1 = new FakeRandom();
		$random1->setSeed( 100 );

		$random2 = new FakeRandom();
		$random2->setSeed( 100 );

		$this->assertEquals( $random1->uniqueId(), $random2->uniqueId() );
	}

	public function testStringHexWithSeed(): void
	{
		$random = new FakeRandom();
		$random->setSeed( 42 );

		$string = $random->string( 32, 'hex' );

		$this->assertIsString( $string );
		$this->assertEquals( 32, strlen( $string ) );
		$this->assertMatchesRegularExpression( '/^[0-9a-f]+$/', $string );
	}

	public function testStringAlphanumericWithSeed(): void
	{
		$random = new FakeRandom();
		$random->setSeed( 42 );

		$string = $random->string( 32, 'alphanumeric' );

		$this->assertIsString( $string );
		$this->assertEquals( 32, strlen( $string ) );
		$this->assertMatchesRegularExpression( '/^[a-zA-Z0-9]+$/', $string );
	}

	public function testStringAlphaWithSeed(): void
	{
		$random = new FakeRandom();
		$random->setSeed( 42 );

		$string = $random->string( 32, 'alpha' );

		$this->assertIsString( $string );
		$this->assertEquals( 32, strlen( $string ) );
		$this->assertMatchesRegularExpression( '/^[a-zA-Z]+$/', $string );
	}

	public function testStringNumericWithSeed(): void
	{
		$random = new FakeRandom();
		$random->setSeed( 42 );

		$string = $random->string( 32, 'numeric' );

		$this->assertIsString( $string );
		$this->assertEquals( 32, strlen( $string ) );
		$this->assertMatchesRegularExpression( '/^[0-9]+$/', $string );
	}

	public function testStringIsPredictable(): void
	{
		$random1 = new FakeRandom();
		$random1->setSeed( 100 );

		$random2 = new FakeRandom();
		$random2->setSeed( 100 );

		$this->assertEquals( $random1->string( 32, 'hex' ), $random2->string( 32, 'hex' ) );
	}

	public function testStringWithInvalidCharsetThrowsException(): void
	{
		$random = new FakeRandom();

		$this->expectException( \InvalidArgumentException::class );
		$this->expectExceptionMessage( 'Unknown charset: invalid' );

		$random->string( 10, 'invalid' );
	}

	public function testFloatWithSeed(): void
	{
		$random = new FakeRandom();
		$random->setSeed( 500 );

		$float = $random->float();

		$this->assertIsFloat( $float );
		$this->assertGreaterThanOrEqual( 0.0, $float );
		$this->assertLessThanOrEqual( 1.0, $float );
	}

	public function testFloatIsPredictable(): void
	{
		$random1 = new FakeRandom();
		$random1->setSeed( 100 );

		$random2 = new FakeRandom();
		$random2->setSeed( 100 );

		$this->assertEquals( $random1->float(), $random2->float() );
	}

	public function testShuffleReversesArray(): void
	{
		$random = new FakeRandom();
		$original = [1, 2, 3, 4, 5];

		$shuffled = $random->shuffle( $original );

		$this->assertEquals( [5, 4, 3, 2, 1], $shuffled );
	}

	public function testShufflePreservesElements(): void
	{
		$random = new FakeRandom();
		$original = ['a', 'b', 'c', 'd', 'e'];

		$shuffled = $random->shuffle( $original );

		sort( $original );
		sort( $shuffled );
		$this->assertEquals( $original, $shuffled );
	}

	public function testShuffleIsPredictable(): void
	{
		$random = new FakeRandom();
		$array = [1, 2, 3, 4, 5];

		$result1 = $random->shuffle( $array );
		$result2 = $random->shuffle( $array );

		$this->assertEquals( $result1, $result2 );
	}

	public function testSeedChangesResults(): void
	{
		$random = new FakeRandom();

		$random->setSeed( 42 );
		$result1 = $random->int( 1, 1000 );

		$random->setSeed( 100 );
		$result2 = $random->int( 1, 1000 );

		$this->assertNotEquals( $result1, $result2 );
	}
}
