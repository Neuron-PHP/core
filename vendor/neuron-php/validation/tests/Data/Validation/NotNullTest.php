<?php

class NotNullTest extends PHPUnit\Framework\TestCase
{
	public function testNotNull()
	{
		$Validator = new \Neuron\Data\Validation\NotNull();

		$this->assertTrue( $Validator->isValid( 'test' ) );
		$this->assertTrue( $Validator->isValid( '' ) );
		$this->assertTrue( $Validator->isValid( 0 ) );
		$this->assertTrue( $Validator->isValid( false ) );
		$this->assertFalse( $Validator->isValid( null ) );
	}
}
