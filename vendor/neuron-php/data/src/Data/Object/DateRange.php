<?php

namespace Neuron\Data\Object;

use Neuron\Data\Formatter;
use Neuron\Data\Date;

class DateRange
{
	public $Start;
	public $End;

	public function __construct( $Start, $End )
	{
		$this->Start = Formatter::dateOnly( $Start );
		$this->End   = Formatter::dateOnly( $End );
	}

	/**
	 * Returns the number of days between start and end.
	 * @return int
	 */
	public function getLengthInDays()
	{
		return Date::diff( $this->End, $this->Start );
	}
}
