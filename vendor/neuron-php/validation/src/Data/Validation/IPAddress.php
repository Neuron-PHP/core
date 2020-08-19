<?php


namespace Neuron\Data\Validation;

/**
 * Requires a valid IPAddress format.
 * @package Neuron\Data\Validation
 */
class IPAddress extends Base
{
	protected function validate( $address )
	{
		return filter_var( $address, FILTER_VALIDATE_IP )? true : false;
	}
}
