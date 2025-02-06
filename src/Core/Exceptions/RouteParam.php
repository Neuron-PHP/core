<?php

namespace Neuron\Core\Exceptions;

class RouteParam extends Base
{
	public function __construct( string $message )
	{
		parent::__construct( $message );
	}
}
