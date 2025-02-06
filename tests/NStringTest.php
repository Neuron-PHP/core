<?php

namespace Tests;
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
		$this->String = new \Neuron\Core\NString( $this::DATA );

		parent::setUp();
	}

	public function testConstruct()
	{
		$this->String = new \Neuron\Core\NString( $this::DATA );

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

		$this->assertEquals( 'ThisIsATest', $this->String->toCamelCase() );
	}

	public function testToCamelCaseNoCap()
	{
		$this->String->value = 'this_is_a_test';

		$this->assertEquals( 'thisIsATest', $this->String->toCamelCase( false ) );
	}

	public function testToSnakeCase()
	{
		$this->String->value = 'ThisIsATest';

		$this->assertEquals( 'this_is_a_test', $this->String->toSnakeCase() );
	}
}
