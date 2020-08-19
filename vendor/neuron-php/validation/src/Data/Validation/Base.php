<?php

namespace Neuron\Data\Validation;

/**
 * Validator base class.
 */

abstract class Base implements IValidator
{
	abstract protected function validate( $data );

	public function __construct()
	{
		return $this;
	}

	/**
	 * Returns true if validation is successful
	 *
	 * @param $data
	 * @return bool
	 */

	public function isValid( $data ) : bool
	{
		return $this->validate( $data );
	}
}
