<?php

namespace Neuron\Data\Validation;

/**
 * Requires a number with a maximum of 2 decimal places.
 * @package Neuron\Data\Validation
 */
class Currency extends Base
{
	public function validate( $data )
	{
		return preg_match("/^-?[0-9]+(?:\.[0-9]{1,2})?$/", $data ) == 1 ? true : false;
	}
}
