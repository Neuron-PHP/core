<?php

use Neuron\Data\Parser;
use PHPUnit\Framework\TestCase;

class FirstMITest extends PHPUnit\Framework\TestCase
{

	public function testParse()
	{
		$Parser = new Parser\FirstMI();

		list( $First, $Middle ) = $Parser->parse( "Alfred E" );

		$this->assertEquals( $First,	'Alfred' );
		$this->assertEquals( $Middle,	'E' );
	}

	public function testParseWithPeriod()
	{
		$Parser = new Parser\FirstMI();

		list( $First, $Middle ) = $Parser->parse( "Alfred E." );

		$this->assertEquals( $First,	'Alfred' );
		$this->assertEquals( $Middle,	'E' );
	}

	public function testFirstOnly()
	{
		$Parser = new Parser\FirstMI();

		list( $First, $Middle ) = $Parser->parse( "Alfred" );

		$this->assertEquals( $First,	'Alfred' );
		$this->assertEquals( $Middle,	'' );
	}
}
