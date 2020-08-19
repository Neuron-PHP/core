<?php

/**
 * Created by PhpStorm.
 * User: lee
 * Date: 8/13/16
 * Time: 5:48 PM
 */
class LogTestBase extends PHPUnit\Framework\TestCase
{
	const INPUT = 'Test log.';
	public $Data;

	public function setup()
	{
		$this->Data = new \Neuron\Log\Data( time(), self::INPUT, \Neuron\Log\ILogger::DEBUG, 'DEBUG' );
	}
}
