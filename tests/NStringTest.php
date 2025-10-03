<?php

namespace Tests;
use Neuron\Core\NString;
use PHPUnit;

/**
 * Created by PhpStorm.
 * User: lee
 * Date: 1/31/17
 * Time: 9:58 AM
 */
class NStringTest extends PHPUnit\Framework\TestCase
{
	const DATA = '123456789';

	public $String;

	protected function setUp(): void
	{
		$this->String = new NString( $this::DATA );

		parent::setUp();
	}

	public function testConstruct()
	{
		$this->String = new NString( $this::DATA );

		$this->assertEquals( $this::DATA, $this->String->value );
	}

	public function testValue()
	{
		$this->String->value = '1234';

		$this->assertEquals( '1234', $this->String->value );
	}

	public function testLength()
	{
		$this->assertEquals( 9, $this->String->length() );
	}

	public function testLeft()
	{
		$this->assertEquals( '123', $this->String->left( 3 ) );
	}

	public function testRight()
	{
		$this->assertEquals( '789', $this->String->right( 3 ) );
	}

	public function testMid()
	{
		$this->assertEquals( '5678', $this->String->mid( 4, 7 ) );
	}

	public function testTrim()
	{
		$this->String->value = ' 123 ';

		$this->assertEquals( '123', $this->String->trim() );
	}

	public function testDeQuote()
	{
		$this->String->value = '"123"';

		$this->assertEquals( '123', $this->String->deQuote() );
	}

	public function testQuote()
	{
		$this->String->value = ' 123 ';

		$this->assertEquals( '"123"', $this->String->quote() );
	}

	public function testToCamelCase()
	{
		$this->String->value = 'this_is_a_test';

		$this->assertEquals( 'thisIsATest', $this->String->toCamelCase() );
	}

	public function testToPascalCase()
	{
		$this->String->value = 'this_is_a_test';

		$this->assertEquals( 'ThisIsATest', $this->String->toPascalCase() );
	}

	public function testToSnakeCase()
	{
		$this->String->value = 'ThisIsATest';

		$this->assertEquals( 'this_is_a_test', $this->String->toSnakeCase() );
	}

	public function testToUpper()
	{
		$this->String->value = 'Hello World';

		$this->assertEquals( 'HELLO WORLD', $this->String->toUpper() );
	}

	public function testToLower()
	{
		$this->String->value = 'Hello World';

		$this->assertEquals( 'hello world', $this->String->toLower() );
	}

	public function testToUpperWithMixedCase()
	{
		$this->String->value = 'HeLLo WoRLd 123';

		$this->assertEquals( 'HELLO WORLD 123', $this->String->toUpper() );
	}

	public function testToLowerWithMixedCase()
	{
		$this->String->value = 'HeLLo WoRLd 123';

		$this->assertEquals( 'hello world 123', $this->String->toLower() );
	}
}
