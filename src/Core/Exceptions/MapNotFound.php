<?php

namespace Neuron\Core\Exceptions;

/**
 *
 */

class MapNotFound extends NotFound
{
	public function __construct( string $Name )
	{
		parent::__construct( "Missing map to: $Name" );
	}
}
