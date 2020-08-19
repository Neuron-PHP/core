<?php

namespace Neuron\Data\Validation;

/**
 * Requires a positive number.
 * @package Neuron\Data\Validation
 */
class Positive extends Base
{
	public function validate( $Data )
	{
		return !( $Data < 0 );
	}
}
