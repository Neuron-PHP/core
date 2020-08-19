<?php

/**
 * Created by PhpStorm.
 * User: lee
 * Date: 8/3/16
 * Time: 11:11 AM
 */
class NotEmptyTest extends PHPUnit\Framework\TestCase
{
	public function testNotEmpty()
	{
		$Validator = new \Neuron\Data\Validation\NotEmpty();

		$this->assertTrue( $Validator->isValid( 'test' ) );
		$this->assertFalse( $Validator->isValid( '' ) );
		$this->assertFalse( $Validator->isValid( 0 ) );
		$this->assertFalse( $Validator->isValid( false ) );
		$this->assertFalse( $Validator->isValid( null ) );
	}
}
