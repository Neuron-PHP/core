<?php

class LogMuxTest extends PHPUnit\Framework\TestCase
{
	public $_Mux;

	public function setUp()
	{
		$this->_Mux = new Neuron\Log\LogMux();

		$this->_Mux->addLog(
			new Neuron\Log\Logger(
				new Neuron\Log\Destination\Echoer(
					new Neuron\Log\Format\PlainText( false )
				)
			),
			Neuron\Log\ILogger::INFO
		);

		$this->_Mux->addLog(
			new Neuron\Log\Logger(
				new Neuron\Log\Destination\Echoer(
					new Neuron\Log\Format\JSON()
				)
			),
			Neuron\Log\ILogger::WARNING
		);

	}

	public function testPass()
	{
		$this->_Mux->setRunLevel( Neuron\Log\ILogger::DEBUG );
		$test = 'this is a test';

		ob_start();

		$this->_Mux->info( $test );

		$s = ob_get_contents();

		ob_end_clean();

		$this->assertTrue( strstr( $s, $test ) ? true : false );

		ob_start();

		$this->_Mux->warning( $test );

		$s = ob_get_contents();

		ob_end_clean();

		$this->assertTrue( strstr( $s, "\"text\":\"$test\"" ) ? true : false );
	}

	public function testFail()
	{
		$this->_Mux->setRunLevel( Neuron\Log\ILogger::INFO );
		$test = 'this is a test';

		ob_start();

		$this->_Mux->debug( $test );

		$s = ob_get_contents();

		ob_end_clean();

		$this->assertTrue( $s == '' );
	}
}
