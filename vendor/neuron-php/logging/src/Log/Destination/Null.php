<?php

namespace Neuron\Log\Destination;

use Neuron\Log;

/**
 * Performs to output. Use as dev/null.
 */

class Null
	extends DestinationBase
{
	/**
	 * @param array $Params
	 * @return bool
	 *
	 * @SuppressWarnings(PHPMD)
	 */

	public function open( array $Params ) : bool
	{
		return true;
	}

	public function close()
	{

	}

	/**
	 * @param $s
	 * @param Log\Data $Data
	 *
	 * @SuppressWarnings(PHPMD)
	 */

	public function write( string $Text, Log\Data $Data )
	{
		// asm nop;
	}
}
