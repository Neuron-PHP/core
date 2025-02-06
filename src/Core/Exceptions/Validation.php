<?php

namespace Neuron\Core\Exceptions;

class Validation extends Base
{
	public array $errors
	{
		get
		{
			return $this->errors;
		}
	}

	public function __construct( string $name, array $errors )
	{
		parent::__construct( "Validation failed for $name" );
		$this->errors = $errors;
	}
}
