<?php

namespace Neuron\Data\Validation;

/**
 * Requires a valid phone number format nnn-nnn-nnnn
 * @package Neuron\Data\Validation
 */
class PhoneNumber extends Base
{
	const US            = 10;
	const INTERNATIONAL = 20;

	const US_PATTERN            = "/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/";
	const INTERNATIONAL_PATTERN = "/^\+(?:[0-9] ?){6,14}[0-9]$/";

	public $_Type;

	/**
	 * @return int
	 */
	public function getType(): int
	{
		return $this->_Type;
	}

	/**
	 * @param int $Type
	 * @return PhoneNumber
	 */
	public function setType( int $Type ): PhoneNumber
	{
		$this->_Type = $Type;
		return $this;
	}

	public function __construct()
	{
		$this->_Type = self::US;

		return parent::__construct();
	}

	public function validate( $data )
	{
		if( $this->getType() == self::INTERNATIONAL )
		{
			return preg_match(self::INTERNATIONAL_PATTERN, $data ) == 1 ? true : false;
		}

		return preg_match(self::US_PATTERN, $data ) == 1 ? true : false;
	}
}
