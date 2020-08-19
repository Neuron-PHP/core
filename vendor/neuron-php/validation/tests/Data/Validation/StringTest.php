<?php
class StringTest extends PHPUnit\Framework\TestCase
{
	public function testFail()
	{
		$dn = new \Neuron\Data\Validation\StringData();

		$this->assertFalse( $dn->isValid( 1 ) );
	}

	public function testPass()
	{
		$dn = new \Neuron\Data\Validation\StringData();

		$this->assertTrue( $dn->isValid( 'test@example.org' ) );
	}
}
