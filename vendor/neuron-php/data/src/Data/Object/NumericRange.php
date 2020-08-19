<?php

namespace Neuron\Data\Object;

class NumericRange
{
	public $Minimum;
	public $Maximum;

	public function __construct( $Minimum, $Maximum )
	{
		$this->Minimum = $Minimum;
		$this->Maximum = $Maximum;
	}
}
