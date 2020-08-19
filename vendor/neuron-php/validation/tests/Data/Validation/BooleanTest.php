<?php

class BooleanTestTest extends PHPUnit\Framework\TestCase
{
	public function testFail()
	{
		$Validator = new \Neuron\Data\Validation\Boolean();

		$this->assertFalse( $Validator->isValid( 'string' ) );
	}

	public function testPass()
	{
		$Validator = new \Neuron\Data\Validation\Boolean();

		$this->assertTrue( $Validator->isValid( true ) );
	}

	public function testLoose()
	{
		$Validator = new \Neuron\Data\Validation\Boolean( true );

		$this->assertTrue( $Validator->isValid( 0 ) );
		$this->assertTrue( $Validator->isValid( 1 ) );

		$this->assertTrue( !$Validator->isValid( 2 ) );
		$this->assertTrue( !$Validator->isValid( 'test' ) );

		$this->assertTrue( $Validator->isValid( '0' ) );
		$this->assertTrue( $Validator->isValid( '1' ) );

		$this->assertTrue( $Validator->isValid( 'TRUE' ) );
		$this->assertTrue( $Validator->isValid( 'FALSE' ) );

		$this->assertTrue( $Validator->isValid( 'on' ) );
		$this->assertTrue( $Validator->isValid( 'yes' ) );
		$this->assertTrue( $Validator->isValid( 'off' ) );
		$this->assertTrue( $Validator->isValid( 'no' ) );
	}
}
