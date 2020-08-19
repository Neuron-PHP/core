<?php
/**
 * Created by PhpStorm.
 * User: lee
 * Date: 7/27/16
 * Time: 6:43 PM
 */

namespace Neuron\Data\Validation;

use Neuron\Data\Object\DateRange;

/**
 * Requires a date within a specified range.
 * @package Neuron\Data\Validation
 */
class DateWithinRange extends Date
{
	private $_Range;

	protected function validate( $date )
	{
		if( !parent::validate( $date ) )
		{
			return false;
		}

		$startTS = strtotime( $this->_Range->Start );
		$endTS   = strtotime( $this->_Range->End );
		$dateTS  = strtotime( $date );

		return ( ( $dateTS >= $startTS ) && ( $dateTS <= $endTS ) );
	}

	/**
	 * @param $Range
	 * @return $this
	 */

	public function setRange( DateRange $Range )
	{
		$this->_Range = $Range;

		return $this;
	}
}
