<?php

use Neuron\Data\Parser;

class LastFirstMITest extends PHPUnit\Framework\TestCase
{
	protected function setUp()
	{
	}

	public function testPass()
	{
		$Parser = new Parser\LastFirstMI();


		list( $sFirst, $sMiddle, $sLast ) = $Parser->parse( "Newman, Alfred E" );

		$this->assertEquals( $sFirst,		'Alfred' );
		$this->assertEquals( $sMiddle,	'E' );
		$this->assertEquals( $sLast,		'Newman' );
	}

	protected function tearDown()
	{
	}
}
