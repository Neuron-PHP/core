<?php
/**
 * Created by PhpStorm.
 * User: lee
 * Date: 6/24/18
 * Time: 7:39 AM
 */

namespace Neuron\Data\Validation;

interface ICollection extends IValidator
{
	/**
	 * @param $Name
	 * @param IValidator $Validator
	 * @return $this
	 *
	 * Add a validator to the collection.
	 */
	public function add( $Name, IValidator $Validator );

	/**
	 * @return mixed
	 *
	 * Returns the list of failed validations.
	 */
	public function getViolations(): array;
}
