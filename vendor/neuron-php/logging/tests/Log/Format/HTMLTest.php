<?php
require_once 'tests/Log/Format/LogTestBase.php';

class HTMLTest extends \LogTestBase
{
	public function testFormat()
	{
		$Html = new \Neuron\Log\Format\HTML();

		$out = $Html->format( $this->Data );

		$this->assertTrue(
			strstr( $out, '<small>' ) != false
		);
	}
}
