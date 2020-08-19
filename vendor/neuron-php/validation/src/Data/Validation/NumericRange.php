<?php

namespace Neuron\Data\Validation;

use Neuron\Data;

/**
 * Requires the Minimum to be less than the Maximum.
 * @package Neuron\Data\Validation
 */
class NumericRange extends Base
{
	protected function validate( $data )
	{
		return ( $data->Minimum < $data->Maximum );
	}
}
