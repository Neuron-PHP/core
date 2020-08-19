<?php

namespace Neuron\Log;

use Neuron\Log\Destination\Echoer;
use Neuron\Log\Format\PlainText;
use Neuron\Patterns\Singleton\Memory;

class Log extends Memory
{
	public $Logger;

	/**
	 * Creates and initializes the core logger if needed.
	 */
	public function initIfNeeded()
	{
		if( !$this->Logger )
		{
			$this->Logger = new LogMux();

			$this->Logger->addLog(
				new Logger(
					new Echoer(
						new PlainText()
					)
				)
			);

			$this->serialize();
		}
	}

	/**
	 * @param $text
	 * @param $iLevel
	 */
	public static function _log( string $text, int $iLevel )
	{
		$Log = self::getInstance();
		$Log->initIfNeeded();
		$Log->Logger->log( $text, $iLevel );
	}

	/**
	 * @param $iLevel
	 */
	public static function setRunLevel( int $iLevel )
	{
		$Log = self::getInstance();
		$Log->initIfNeeded();
		$Log->Logger->setRunLevel( $iLevel );
		$Log->serialize();
	}

	/**
	 * @param $text
	 */
	public static function debug( string $text )
	{
		self::_log( $text, ILogger::DEBUG );
	}

	/**
	 * @param $text
	 */
	public static function info( string $text )
	{
		self::_log( $text, ILogger::INFO );
	}

	/**
	 * @param $text
	 */
	public static function warning( string $text )
	{
		self::_log( $text, ILogger::WARNING );
	}

	/**
	 * @param $text
	 */
	public static function error( string $text )
	{
		self::_log( $text, ILogger::ERROR );
	}

	/**
	 * @param $text
	 */
	public static function fatal( string $text )
	{
		self::_log( $text, ILogger::FATAL );
	}
}
