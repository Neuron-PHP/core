<?php
require_once 'tests/Log/Format/LogTestBase.php';

class PlainTextText extends \LogTestBase
{
	public function testFormat()
	{
		$Text = new \Neuron\Log\Format\PlainText();

		$out = $Text->format( $this->Data );

		$this->assertTrue(
			strstr( $out, self::INPUT ) != false
		);
	}
}
