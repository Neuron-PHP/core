<?php

namespace Neuron\Log\Format;

use Neuron\Log;

class HTMLEmail implements IFormat
{
	public function format( Log\Data $Data ): string
	{
		return
			'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
				<html xmlns="http://www.w3.org/1999/xhtml">
				 <head>
				  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
				  <title>HTMLEmail</title>
				  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
				</head>
				<body>
					<table>
						<tr><td>Time</td><td>'.date( "[Y-m-d G:i:s]", $Data->TimeStamp ).'</td></tr>
						<tr><td>Type</td><td>'.$Data->LevelText.'</td></tr>
						<tr><td>Text</td><td>'.$Data->Text.'</td></tr>
					</table>
				</body>
			</html>';
	}
}
