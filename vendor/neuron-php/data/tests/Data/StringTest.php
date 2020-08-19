<?php

/**
 * Created by PhpStorm.
 * User: lee
 * Date: 1/31/17
 * Time: 9:58 AM
 */
class StringDataTest extends PHPUnit\Framework\TestCase
{
	const DATA = '123456789';

	public $String;

	public function setUp()
	{
		$this->String = new \Neuron\Data\StringData( $this::DATA );

		parent::setUp();
	}

	public function testConstruct()
	{
		$this->String = new \Neuron\Data\StringData( $this::DATA );

		$this->assertEquals(
			$this::DATA,
			$this->String->Value
		);
	}

	public function testValue()
	{
		$this->String->Value = '1234';

		$this->assertEquals(
			'1234',
			$this->String->Value
		);
	}

	public function testLength()
	{
		$this->assertEquals(
			9,
			$this->String->length()
		);
	}

	public function testLeft()
	{
		$this->assertEquals(
			'123',
			$this->String->left( 3 )
		);
	}

	public function testRight()
	{
		$this->assertEquals(
			'789',
			$this->String->right( 3 )
		);
	}

	public function testMid()
	{
		$this->assertEquals(
			'5678',
			$this->String->mid( 4, 7 )
		);
	}

	public function testTrim()
	{
		$this->String->Value = ' 123 ';

		$this->assertEquals(
			'123',
			$this->String->trim()
		);
	}

	public function testDeQuote()
	{
		$this->String->Value = '"123"';

		$this->assertEquals(
			'123',
			$this->String->deQuote()
		);
	}

	public function testQuote()
	{
		$this->String->Value = ' 123 ';

		$this->assertEquals(
			'"123"',
			$this->String->quote()
		);
	}

	public function testToCamelCase()
	{
		$this->String->Value = 'this_is_a_test';

		$this->assertEquals(
			'ThisIsATest',
			$this->String->toCamelCase()
		);
	}

	public function testToCamelCaseNoCap()
	{
		$this->String->Value = 'this_is_a_test';

		$this->assertEquals(
			'thisIsATest',
			$this->String->toCamelCase( false )
		);
	}

	public function testToSnakeCase()
	{
		$this->String->Value = 'ThisIsATest';

		$this->assertEquals(
			'this_is_a_test',
			$this->String->toSnakeCase()
		);
	}
}
