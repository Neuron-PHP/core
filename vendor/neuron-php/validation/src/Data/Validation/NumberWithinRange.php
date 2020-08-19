<?php

namespace Neuron\Data\Validation;

use Neuron\Data;

/**
 * Requires a number to be within a specific range.
 * @package Neuron\Data\Validation
 */
class NumberWithinRange extends Base
{
	private $_Range;

	protected function validate( $data )
	{
		return ( ( $data >= $this->_Range->Minimum ) && ( $data <= $this->_Range->Maximum ) );
	}

	public function setRange( Data\Object\NumericRange $Range )
	{
		$this->_Range = $Range;

		return $this;
	}
}
