<?php

namespace Neuron\Data\Validation;

interface IValidator
{
	public function isValid( $data ) : bool;
}
