<?php

use Neuron\Data\Validation\Numeric;

class NumericTest extends PHPUnit\Framework\TestCase
{
	protected $_Validator;
	
	protected function setUp()
	{
		parent::setUp();
		
		$this->_Validator = new Numeric();
	}

	public function testPass()
	{
		$this->assertTrue(
			$this->_Validator->isValid( 1 )
		);

		$this->assertTrue(
			$this->_Validator->isValid( '1' )
		);

		$this->assertTrue(
			$this->_Validator->isValid( 1.25 )
		);

		$this->assertTrue(
			$this->_Validator->isValid( '1.25' )
		);
	}
	
	public function testFail()
	{
		$this->assertFalse(
			$this->_Validator->isValid( '123abc' )
		);
	}
}
