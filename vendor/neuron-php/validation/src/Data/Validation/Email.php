<?php

namespace Neuron\Data\Validation;

/**
 * Requires a valid email address format.
 * @package Neuron\Data\Validation
 */
class Email extends Base
{
	protected function validate( $email )
	{
		return filter_var( $email, FILTER_VALIDATE_EMAIL ) ? true : false;
	}
}
