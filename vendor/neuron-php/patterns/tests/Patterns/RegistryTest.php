<?php

use Neuron\Patterns\Registry;

class RegistrylTest extends PHPUnit\Framework\TestCase
{
	protected function setUp()
	{
	}

	public function testPass()
	{
		$Reg1 = Registry::getInstance();

		$Reg1->set( 'test', '1234' );

		$Reg2 = Registry::getInstance();

		$this->assertEquals( $Reg2->get( 'test' ), '1234' );
	}

	public function testFail()
	{
		$Reg1 = Registry::getInstance();

		$Reg1->set( 'test', '1234' );

		$Reg2 = Registry::getInstance();

		$this->assertNotEquals( $Reg2->get( 'test' ), '1111' );
	}

	protected function tearDown()
	{
	}
}
