<?php
/**
 * Created by PhpStorm.
 * User: lee
 * Date: 8/4/16
 * Time: 11:41 AM
 */

namespace Neuron\Patterns\Criteria;

class AndCriteria extends LogicBase implements ICriteria
{
	/**
	 * @param array $Entities
	 * @return array
	 */

	public function meetCriteria( array $Entities )
	{
		$Result = $this->_Criteria->meetCriteria( $Entities );

		if( count( $Result ) == 0 )
		{
			return $Result;
		}

		return $this->_OtherCriteria->meetCriteria( $Result );
	}
}
