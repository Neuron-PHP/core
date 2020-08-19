<?php

namespace Neuron\Data\Validation;

/**
 * Requires a non empty variable.
 * @package Neuron\Data\Validation
 */
class NotEmpty extends Base
{
	protected function validate( $data )
	{
		return !empty( $data );
	}
}
