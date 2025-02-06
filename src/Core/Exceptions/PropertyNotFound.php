<?php

namespace Neuron\Core\Exceptions;

/**
 *
 */

class PropertyNotFound extends NotFound
{
	public function __construct( string $Name )
	{
		parent::__construct( "Missing map to: $Name" );
	}
}
