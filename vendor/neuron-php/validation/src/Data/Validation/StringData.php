<?php

namespace Neuron\Data\Validation;

/**
 * Requires a string.
 * @package Neuron\Data\Validation
 */
class StringData extends Base
{
	protected function validate( $string )
	{
		return is_string( $string );
	}
}
