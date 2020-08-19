<?php

namespace Neuron\Data\Validation;

/**
 * Requires time to be in a specific formate. Defaults to g:i:s A
 * @package Neuron\Data\Validation
 */
class Time extends Base
{
	private $_sFormat = 'g:i:s A';

	protected function validate( $CheckTime )
	{
		$Time = \DateTime::createFromFormat( $this->_sFormat, $CheckTime );
		return $Time && $Time->format( $this->_sFormat ) == $CheckTime;
	}

	/**
	 * @param $sFormat - Specify the date format to validate to. Defaults to g:i:s A
	 */

	public function setFormat( $sFormat )
	{
		$this->_sFormat = $sFormat;
	}
}
