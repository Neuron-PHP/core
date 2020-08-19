<?php

namespace Neuron\Data\Object;

/**
 * Class GpsPoint
 * @package Neuron\Data\Object
 */

class GpsPoint
{
	public function __construct( $Latitude = 0.0, $Longitude = 0.0 )
	{
		$this->Latitude  = $Latitude;
		$this->Longitude = $Longitude;
	}

	public $Latitude;
	public $Longitude;
}
