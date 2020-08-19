<?php
require_once 'tests/Log/Format/LogTestBase.php';

class CSVFormatTest extends \LogTestBase
{
	public function testFormat()
	{
		$Csv = new \Neuron\Log\Format\CSV();

		$out = $Csv->format( $this->Data );

		$aParts = explode( ',', $out );

		$this->assertEquals(
			count( $aParts ),
			3
		);
	}
}
