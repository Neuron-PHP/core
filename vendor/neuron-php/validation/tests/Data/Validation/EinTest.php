<?php

class EinTest extends PHPUnit\Framework\TestCase
{
	public function testFail()
	{
		$Integer = new \Neuron\Data\Validation\Ein();

		$this->assertFalse( $Integer->isValid( '114411-121x' ) );
	}

	public function testPass()
	{
		$Integer = new \Neuron\Data\Validation\Ein();

		$this->assertTrue( $Integer->isValid( '65-1234567') );
	}
}
