<?php

use Neuron\Log\Log;

class LogSingletonTest extends PHPUnit\Framework\TestCase
{
	public function setUp()
	{
		parent::setUp();
	}

	public function testPass()
	{
		Log::setRunLevel( Neuron\Log\ILogger::DEBUG );
		$test = 'this is a test';

		ob_start();

		Log::_log( $test, Neuron\Log\ILogger::INFO );

		$str = ob_get_contents();

		ob_end_clean();

		$this->assertTrue( strstr( $str, $test ) ? true : false );
	}

	public function testFail()
	{
		Log::setRunLevel( Neuron\Log\ILogger::INFO );
		$test = 'this is a test';

		ob_start();

		Log::_log( $test, Neuron\Log\ILogger::DEBUG );

		$str = ob_get_contents();

		ob_end_clean();

		$this->assertTrue( $str == '' );
	}

	public function testDebug()
	{
		Log::setRunLevel( Neuron\Log\ILogger::DEBUG );
		$test = 'this is a test';

		ob_start();

		Log::debug( $test );

		$str = ob_get_contents();

		ob_end_clean();

		$this->assertTrue( strstr( $str, $test ) ? true : false );
	}

	public function testInfo()
	{
		Log::setRunLevel( Neuron\Log\ILogger::INFO );
		$test = 'this is a test';

		ob_start();

		Log::info( $test );

		$str = ob_get_contents();

		ob_end_clean();

		$this->assertTrue( strstr( $str, $test ) ? true : false );
	}

	public function testWarning()
	{
		Log::setRunLevel( Neuron\Log\ILogger::WARNING );
		$test = 'this is a test';

		ob_start();

		Log::warning( $test );

		$str = ob_get_contents();

		ob_end_clean();

		$this->assertTrue( strstr( $str, $test ) ? true : false );
	}

	public function testError()
	{
		Log::setRunLevel( Neuron\Log\ILogger::ERROR );
		$test = 'this is a test';

		ob_start();

		Log::error( $test );

		$str = ob_get_contents();

		ob_end_clean();

		$this->assertTrue( strstr( $str, $test ) ? true : false );
	}

	public function testFatal()
	{
		Log::setRunLevel( Neuron\Log\ILogger::FATAL );
		$test = 'this is a test';

		ob_start();

		Log::fatal( $test );

		$str = ob_get_contents();

		ob_end_clean();

		$this->assertTrue( strstr( $str, $test ) ? true : false );
	}
}
