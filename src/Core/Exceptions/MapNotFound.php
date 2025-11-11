<?php

namespace Neuron\Core\Exceptions;

/**
 *
 */

class MapNotFound extends NotFound
{
	public function __construct( string $name )
	{
		parent::__construct( "Missing map to: $name" );
	}
}
