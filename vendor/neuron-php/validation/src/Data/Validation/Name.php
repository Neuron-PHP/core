<?php

namespace Neuron\Data\Validation;

/**
 * Requires a valid name.
 * @todo require at least one space.
 *
 * Allows:
 * 	Upper case letters.
 * 	Lower case letters.
 * 	. and ,
 *
 * @package Neuron\Data\Validation
 */
class Name extends Base
{
	public function validate( $data )
	{
		return preg_match("/^[a-zA-Z,. ]*$/", $data ) == 1 ? true : false;
	}
}
