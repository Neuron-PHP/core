<?php

class CSVTest extends PHPUnit\Framework\TestCase
{
	protected function setUp()
	{
	}

	public function testCSV()
	{
		$Parser = new \Neuron\Data\Parser\CSV;

		$aHeaders = array( 'col1', 'col2', 'col3', 'col4' );

		$aRet = $Parser->parse( "text1,text2,text3,text4", $aHeaders );

		$this->assertEquals( 'text1', $aRet[ 'col1' ] );
		$this->assertEquals( 'text2', $aRet[ 'col2' ] );
		$this->assertEquals( 'text3', $aRet[ 'col3' ] );
		$this->assertEquals( 'text4', $aRet[ 'col4' ] );
	}

	protected function tearDown()
	{
	}
}
