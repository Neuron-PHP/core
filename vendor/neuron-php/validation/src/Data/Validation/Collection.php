<?php

namespace Neuron\Data\Validation;

/**
 * Allows multiple validators to be chained in one item.
 * @package Neuron\Data\Validation
 */
class Collection extends Base implements ICollection
{
	private $_Validators;
	private $_Failed;

	protected function validate( $Data )
	{
		$this->_Failed = [];

		array_walk( $this->_Validators, [ $this, 'validateItem' ], $Data );

		return count( $this->_Failed ) > 0 ? false : true;
	}


	public function validateItem( $Validator, $Name, $Data )
	{
		if( !$Validator->isValid( $Data ) )
		{
			$this->_Failed[] = $Name;
		}
	}

	/**
	 * @param $Name
	 * @param IValidator $Validator
	 * @return $this
	 *
	 * Add a validator to the collection.
	 */
	public function add( $Name, IValidator $Validator )
	{
		$this->_Validators[ $Name ] = $Validator;

		return $this;
	}

	/**
	 * @return mixed
	 *
	 * Returns the list of failed validations.
	 */
	public function getViolations() : array
	{
		return $this->_Failed;
	}
}
