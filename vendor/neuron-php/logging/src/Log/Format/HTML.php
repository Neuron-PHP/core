<?php

namespace Neuron\Log\Format;

use Neuron\Log;

/**
 * Formats log data as html.
 */

class HTML implements IFormat
{
	public function format( Log\Data $Data ) : string
	{
		return '<small>'.date( "[Y-m-d G:i:s]", $Data->TimeStamp )."</small> $Data->LevelText $Data->Text<br>";
	}
}
