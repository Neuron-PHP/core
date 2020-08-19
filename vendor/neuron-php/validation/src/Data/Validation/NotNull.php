<?php

namespace Neuron\Data\Validation;

/**
 * Requires a non-null variable.
 * @package Neuron\Data\Validation
 */
class NotNull extends Base
{
	protected function validate( $data )
	{
		return !is_null( $data );
	}
}
