<?php

namespace Neuron\Data\Validation;

/**
 * Requires an integer
 * @package Neuron\Data\Validation
 */
class Integer extends Base
{
	protected function validate( $integer )
	{
		if( is_int( $integer ) )
		{
			return true;
		}

		return ctype_digit( $integer );
	}
}
