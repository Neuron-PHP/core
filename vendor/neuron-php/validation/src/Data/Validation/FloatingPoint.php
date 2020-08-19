<?php

namespace Neuron\Data\Validation;

/**
 * Requires a floating point number.
 * @package Neuron\Data\Validation
 */
class FloatingPoint extends Base
{
	protected function validate( $Value )
	{
		if( is_string( $Value ) )
		{
			return $Value == ( (string)(float)$Value );
		}

		return is_float( $Value );
	}
}
