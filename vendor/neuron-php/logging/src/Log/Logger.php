<?php

namespace Neuron\Log;

/**
 * Class for writing formatted output to specific destinations.
 */

class Logger implements ILogger
{
	private $_iRunLevel = ILogger::ERROR;
	private $_Destination;

	/**
	 * @param Destination\DestinationBase $Dest
	 */

	public function setDestination( Destination\DestinationBase $Dest )
	{
		$this->_Destination = $Dest;
	}

	/**
	 * @return mixed
	 */

	public function getDestination()
	{
		return $this->_Destination;
	}

	/**
	 * @param $iLevel
	 */

	public function setRunLevel( int $iLevel )
	{
		$this->_iRunLevel = $iLevel;
	}

	/**
	 * @return int
	 */

	public function getRunLevel() : int
	{
		return $this->_iRunLevel;
	}

	//////////////////////////////////////////////////////////////////////////

	/**
	 * @param Destination\DestinationBase $Dest
	 */

	public function __construct( Destination\DestinationBase $Dest )
	{
		$this->setDestination( $Dest );
	}

	/**
	 * @return mixed
	 */

	public function open()
	{
		return $this->getDestination()->open();
	}

	/**
	 *
	 */

	public function close()
	{
		$this->getDestination()->close();
	}

	/**
	 * @param $text
	 * @param $iLevel
	 */

	public function log( string $text, int $iLevel )
	{
		if( $iLevel >= $this->getRunLevel() )
		{
			$this->getDestination()->log( $text, $iLevel );
		}
	}

	/**
	 * @param $text
	 */

	public function debug( string $text )
	{
		$this->log( $text, self::DEBUG );
	}

	/**
	 * @param $text
	 */

	public function info( string $text )
	{
		$this->log( $text, self::INFO );
	}

	/**
	 * @param $text
	 */

	public function warning( string $text )
	{
		$this->log( $text, self::WARNING );
	}

	/**
	 * @param $text
	 */

	public function error( string $text )
	{
		$this->log( $text, self::ERROR );
	}

	/**
	 * @param $text
	 */

	public function fatal( string $text )
	{
		$this->log( $text, self::FATAL );
	}
}

