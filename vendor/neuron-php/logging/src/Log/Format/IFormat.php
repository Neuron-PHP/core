<?php

namespace Neuron\Log\Format;

use Neuron\Log;

/**
 * Interface IFormat
 */

interface IFormat
{
	/**
	 * @param Log\Data $data
	 * @return string
	 */
	public function format( Log\Data $data ) : string;
}
