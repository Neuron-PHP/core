<?php

namespace Neuron\Patterns\Criteria;

class KeyValue extends Base implements ICriteria
{
	private $_Key;
	private $_Value;

	public function __construct( $Key, $Value )
	{
		$this->_Key   = $Key;
		$this->_Value = $Value;
	}

	public function meetCriteria( array $Entities )
	{
		$Results = [];

		foreach( $Entities as $Item )
		{
			if( $Item[ $this->_Key ] == $this->_Value )
			{
				$Results[] = $Item;
			}
		}

		return $Results;
	}
}
