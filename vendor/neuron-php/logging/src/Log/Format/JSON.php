<?php

namespace Neuron\Log\Format;

use \Neuron\Log;

/**
 * Formats data as plain text.
 */

class JSON implements IFormat
{
	public function format( Log\Data $Data ) : string
	{
		$aData = [
			'date'	=> date( "[Y-m-d G:i:s]", $Data->TimeStamp ),
			'level'	=> $Data->LevelText,
			'text'	=> $Data->Text
		];
		return json_encode( $aData );
	}
}
