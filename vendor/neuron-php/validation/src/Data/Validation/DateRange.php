<?php

namespace Neuron\Data\Validation;

/**
 * Validates a DateRange data object.
 * @see Date
 * @package Neuron\Data\Validation
 */
class DateRange extends Date
{
	protected function validate( $Range )
	{
		// validate the format of start
		if( !parent::validate( $Range->Start ) )
		{
			return false;
		}

		// validate the format of end
		if( !parent::validate( $Range->End ) )
		{
			return false;
		}

		$startTS = strtotime( $Range->Start );
		$endTS   = strtotime( $Range->End );

		return ( $startTS < $endTS );
	}
}
