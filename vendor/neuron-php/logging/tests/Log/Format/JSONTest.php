<?php
require_once 'tests/Log/Format/LogTestBase.php';

class JsonTest extends \LogTestBase
{
	public function testFormat()
	{
		$Json = new \Neuron\Log\Format\JSON();

		$out = $Json->format( $this->Data );

		$decoded = json_decode( $out, true );

		$this->assertTrue(
			is_array( $decoded )
		);
	}
}
