<?php

use Neuron\Data\Validation\StringLength;
use PHPUnit\Framework\TestCase;

class StringLengthTest extends TestCase
{
	public function testFail()
	{
		$Validator = new StringLength( 10 );

		$this->assertFalse(
			$Validator->isValid( 'sssssssssssss' )
		);
	}

	public function testPass()
	{
		$Validator = new StringLength( 5 );

		$this->assertTrue(
			$Validator->isValid( 'test' )
		);
	}
}
