<?php

/*
 *
 */

namespace Neuron\Log;

/**
 * Interface ILogger
 * @package Neuron\Log
 */

interface ILogger
{
	const DEBUG   = 0;		// Log all
	const INFO    = 10;		// Log informational
	const WARNING = 20;		// Log warning
	const ERROR   = 30;		// Log error
	const FATAL   = 40;		// Log fatal

	/**
	 * @param $text
	 * @param $iLevel
	 */
	public function log( string $text, int $iLevel );

	/**
	 * @param $iLevel
	 * @return mixed
	 */
	public function setRunLevel( int $iLevel );

	/**
	 * @param $text
	 * @return mixed
	 */
	public function debug( string $text );

	/**
	 * @param $text
	 * @return mixed
	 */
	public function info( string $text );

	/**
	 * @param $text
	 * @return mixed
	 */
	public function warning( string $text );

	/**
	 * @param $text
	 * @return mixed
	 */
	public function error( string $text );

	/**
	 * @param $text
	 * @return mixed
	 */
	public function fatal( string $text );
}
