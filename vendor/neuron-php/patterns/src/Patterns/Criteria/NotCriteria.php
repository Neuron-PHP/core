<?php

namespace Neuron\Patterns\Criteria;

use Neuron\Data\ArrayHelper;

class NotCriteria implements ICriteria
{
	private $_Criteria;

	/**
	 * NotCriteria constructor.
	 * @param ICriteria $Criteria
	 */

	public function __construct( ICriteria $Criteria )
	{
		$this->_Criteria = $Criteria;
	}

	/**
	 * @param array $Entities
	 * @return array
	 */

	public function meetCriteria( array $Entities )
	{
		$NotCriteriaItems = $this->_Criteria->meetCriteria( $Entities );

		$NotEntities = $Entities;

		foreach( $NotCriteriaItems as $Item )
		{
			ArrayHelper::remove( $NotEntities, $Item );
		}

		return $NotEntities;
	}
}
