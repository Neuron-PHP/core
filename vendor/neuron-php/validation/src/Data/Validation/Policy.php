<?php

namespace Neuron\Data\Validation;

trait Policy
{
	private $_Rules;

	public function addRule( $Name, IValidator $Validator )
	{
		$this->_Rules[ $Name ] = $Validator;

		return $this;
	}

	public function isRuleValid( $Name, $Value )
	{
		return $this->_Rules[ $Name ]->isValid( $Value );
	}
}
