<?php

/*
 *
 */

//////////////////////////////////////////////////////////////////////////////
//
// Abstract base class for the device level output destinations.
//
//////////////////////////////////////////////////////////////////////////////

namespace Neuron\Log\Destination;

use \Neuron\Log;
use \Neuron\Log\Format;

/**
 * Class DestinationBase
 * @package Neuron\Log\Destination
 */

abstract class DestinationBase
{
	private $_Format;

	/**
	 * @param Format\IFormat $Format
	 */

	public function __construct( Format\IFormat $Format )
	{
		$this->setFormat( $Format );
	}

	/**
	 * @param $Level
	 * @return string
	 */

	public function getLevelText( $Level ) : string
	{
		switch( $Level )
		{
			case Log\ILogger::DEBUG:
				return "Debug";

			case Log\ILogger::INFO:
				return "Info";

			case Log\ILogger::WARNING:
				return "Warning";

			case Log\ILogger::ERROR:
				return "Error";

			case Log\ILogger::FATAL:
				return "Fatal";

			default:
				return "Unknown";
		}
	}

	/**
	 * @param Format\IFormat $Format
	 */

	public function setFormat( Format\IFormat $Format )
	{
		$this->_Format = $Format;
	}

	/**
	 * @param $text - Text m
	 * @param Log\Data $Data
	 * @return mixed
	 */

	protected abstract function write( string $text, Log\Data $Data );

	/**
	 * @param array $Params
	 * @return mixed
	 */

	public abstract function open( array $Params ) : bool;

	/**
	 * @param $Text - Output that has been run through the formatter.
	 * @param $Level - Text output level.
	 */

	public function log( string $Text, int $Level )
	{
		$Log = new Log\Data(
			time(),
			$Text,
			$Level,
			$this->getLevelText( $Level )
		);

		$Text = $this->_Format->format( $Log );

		$this->write( $Text, $Log );
	}
}
