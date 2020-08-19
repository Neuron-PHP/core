<?php

namespace Neuron\Log;

class Base implements ILogger
{
	private $_Logger;

	/**
	 * @param ILogger $Logger
	 */

	public function __construct( ILogger $Logger )
	{
		$this->_Logger = $Logger;
	}

	/**
	 * @return ILogger
	 */

	public function getLogger() : ILogger
	{
		return $this->_Logger;
	}

	/**
	 * @param $text
	 * @param $iLevel
	 *
	 * Writes to the logger. Defaults to debug level.
	 * Data is only written to the log based on the loggers run-level.
	 */

	public function log( string $text, int $iLevel = self::DEBUG )
	{
		$this->_Logger->log( get_class( $this ).': '.$text, $iLevel );
	}

	/**
	 * @param $iLevel
	 */

	public function setRunLevel( int $iLevel )
	{
		$this->_Logger->setRunLevel( $iLevel );
	}

	/**
	 * @param $text
	 */

	public function debug( string $text )
	{
		$this->_Logger->debug( $text );
	}

	/**
	 * @param $text
	 */

	public function info( string $text )
	{
		$this->_Logger->info( $text );
	}

	/**
	 * @param $text
	 */

	public function warning( string $text )
	{
		$this->_Logger->warning( $text );
	}

	/**
	 * @param $text
	 */

	public function error( string $text )
	{
		$this->_Logger->error( $text );
	}

	/**
	 * @param $text
	 */

	public function fatal( string $text )
	{
		$this->_Logger->fatal( $text );
	}
}
