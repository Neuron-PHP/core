<?php
/**
 * Created by PhpStorm.
 * User: lee
 * Date: 8/4/16
 * Time: 11:53 AM
 */

namespace Neuron\Patterns\Criteria;

use Neuron\Data\ArrayHelper;

class OrCriteria extends LogicBase implements ICriteria
{
	/**
	 * @param array $Entities
	 * @return array
	 */

	public function meetCriteria( array $Entities )
	{
		$FirstCriteriaItems = $this->_Criteria->meetCriteria( $Entities );
		$OtherCriteriaItems = $this->_OtherCriteria->meetCriteria( $Entities );

		foreach( $OtherCriteriaItems as $Item )
		{
			if( !ArrayHelper::contains( $FirstCriteriaItems, $Item ) )
			{
				$FirstCriteriaItems[] = $Item;
			}
		}

		return $FirstCriteriaItems;
	}
}
