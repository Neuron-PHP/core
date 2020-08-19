<?php
/**
 * Created by PhpStorm.
 * User: lee
 * Date: 8/4/16
 * Time: 11:40 AM
 */

namespace Neuron\Patterns\Criteria;

interface ICriteria
{
	/**
	 * @param array $Entities
	 * @return array
	 */

	public function meetCriteria( array $Entities );
}
