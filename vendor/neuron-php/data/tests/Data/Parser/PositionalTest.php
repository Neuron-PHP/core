<?php

use Neuron\Data\Parser;

class PositionalTest extends PHPUnit\Framework\TestCase
{
	protected function setUp()
	{
	}

	public function testPass()
	{
		$Parser = new Parser\Positional();


		$aRet = $Parser->parse( "text1,text2,text3,text4",
			[
				[
					'name' 	=> 'col2',
					'start'	=> 6,
					'length'	=> 5
				]
			]);

		$this->assertEquals( 'text2', $aRet[ 'col2' ] );
	}

	protected function tearDown()
	{
	}
}
