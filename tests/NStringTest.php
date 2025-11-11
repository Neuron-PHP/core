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

	public $string;

	protected function setUp(): void
	{
		$this->string = new NString( $this::DATA );

		parent::setUp();
	}

	public function testConstruct()
	{
		$this->string = new NString( $this::DATA );

		$this->assertEquals( $this::DATA, $this->string->value );
	}

	public function testValue()
	{
		$this->string->value = '1234';

		$this->assertEquals( '1234', $this->string->value );
	}

	public function testLength()
	{
		$this->assertEquals( 9, $this->string->length() );
	}

	public function testLeft()
	{
		$this->assertEquals( '123', $this->string->left( 3 ) );
	}

	public function testRight()
	{
		$this->assertEquals( '789', $this->string->right( 3 ) );
	}

	public function testMid()
	{
		$this->assertEquals( '5678', $this->string->mid( 4, 7 ) );
	}

	public function testTrim()
	{
		$this->string->value = ' 123 ';

		$this->assertEquals( '123', $this->string->trim() );
	}

	public function testDeQuote()
	{
		$this->string->value = '"123"';

		$this->assertEquals( '123', $this->string->deQuote() );
	}

	public function testQuote()
	{
		$this->string->value = ' 123 ';

		$this->assertEquals( '"123"', $this->string->quote() );
	}

	public function testToCamelCase()
	{
		$this->string->value = 'this_is_a_test';

		$this->assertEquals( 'thisIsATest', $this->string->toCamelCase() );
	}

	public function testToPascalCase()
	{
		$this->string->value = 'this_is_a_test';

		$this->assertEquals( 'ThisIsATest', $this->string->toPascalCase() );
	}

	public function testToSnakeCase()
	{
		$this->string->value = 'ThisIsATest';

		$this->assertEquals( 'this_is_a_test', $this->string->toSnakeCase() );
	}

	public function testToUpper()
	{
		$this->string->value = 'Hello World';

		$this->assertEquals( 'HELLO WORLD', $this->string->toUpper() );
	}

	public function testToLower()
	{
		$this->string->value = 'Hello World';

		$this->assertEquals( 'hello world', $this->string->toLower() );
	}

	public function testToUpperWithMixedCase()
	{
		$this->string->value = 'HeLLo WoRLd 123';

		$this->assertEquals( 'HELLO WORLD 123', $this->string->toUpper() );
	}

	public function testToLowerWithMixedCase()
	{
		$this->string->value = 'HeLLo WoRLd 123';

		$this->assertEquals( 'hello world 123', $this->string->toLower() );
	}
}
