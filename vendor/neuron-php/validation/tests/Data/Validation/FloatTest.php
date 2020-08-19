<?php

class FloatTest extends PHPUnit\Framework\TestCase
{
	public function testFail()
	{
		$Validator = new \Neuron\Data\Validation\FloatingPoint();

		$this->assertFalse( $Validator->isValid( 'string' ) );
	}

	public function testPass()
	{
		$Validator = new \Neuron\Data\Validation\FloatingPoint();

		$this->assertTrue( $Validator->isValid( 3.14 ) );
	}

	public function testStringPass()
	{
		$Validator = new \Neuron\Data\Validation\FloatingPoint();

		$this->assertTrue( $Validator->isValid( "3.14" ) );
	}
}
