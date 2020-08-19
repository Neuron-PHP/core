<?php

namespace Neuron\Data\Validation;

/**
 * Requires an integer or float or a numeric string
 * @package Neuron\Data\Validation
 */
class Numeric extends Base
{
	protected function validate( $data )
	{
		return is_numeric( $data );
	}
}
