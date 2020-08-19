<?php

namespace Neuron\Data\Validation;

/**
 * Requires a date with a specific format. Defaults to Y-m-d
 * @package Neuron\Data\Validation
 */
class DateTime extends Base
{
	private $_Format = 'Y-m-d H:i:s';

	protected function validate( $CheckDate )
	{
		return date( $this->_Format, strtotime( $CheckDate) ) == $CheckDate;
	}

	/**
	 * @param $Format - Specify the date format to validate to. Defaults to Y-m-d
	 * @return $this
	 */

	public function setFormat( $Format )
	{
		$this->_Format = $Format;

		return $this;
	}
}
