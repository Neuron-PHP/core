<?php

/**
 * Created by PhpStorm.
 * User: lee
 * Date: 9/5/15
 * Time: 11:07 AM
 */

class UrlTest1
	extends PHPUnit\Framework\TestCase
{
	public function testFail1()
	{
		$dn = new \Neuron\Data\Validation\Url();

		$this->assertFalse( $dn->isValid( 'this is a test' ) );
	}

	public function testPass()
	{
		$dn = new \Neuron\Data\Validation\Url();

		$this->assertTrue( $dn->isValid( 'http://example.org' ) );
	}

}
