<?php

namespace Neuron\Data\Validation;

/**
 * Requires an array.
 * @package Neuron\Data\Validation
 */
class ArrayData extends Base
{
	protected function validate( $Array )
	{
		return is_array( $Array );
	}
}
