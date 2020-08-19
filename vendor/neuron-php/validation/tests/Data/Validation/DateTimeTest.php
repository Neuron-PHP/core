<?php

/**
 * Created by PhpStorm.
 * User: lee
 * Date: 9/5/15
 * Time: 11:07 AM
 */
class DateTimeTest extends PHPUnit\Framework\TestCase
{
	public function testFail()
	{
		$dn = new \Neuron\Data\Validation\DateTime();

		$dn->setFormat( 'Y-m-d H:i:s' );

		$this->assertFalse( $dn->isValid( '01-01-2015' ) );
	}

	public function testPass()
	{
		$dn = new \Neuron\Data\Validation\DateTime();

		$dn->setFormat( 'Y-m-d H:i:s' );

		$this->assertTrue( $dn->isValid( '2015-01-01 14:15:16' ) );
	}
}
