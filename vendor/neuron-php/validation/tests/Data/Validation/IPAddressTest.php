<?php

/**
 * Created by PhpStorm.
 * User: lee
 * Date: 9/5/15
 * Time: 11:07 AM
 */

class IPAddressTest
	extends PHPUnit\Framework\TestCase
{
	public function testFail()
	{
		$dn = new \Neuron\Data\Validation\IPAddress();

		$this->assertFalse( $dn->isValid( 'example.org' ) );
	}

	public function testPass()
	{
		$dn = new \Neuron\Data\Validation\IPAddress();

		$this->assertTrue( $dn->isValid( '192.168.1.1' ) );
	}

}
