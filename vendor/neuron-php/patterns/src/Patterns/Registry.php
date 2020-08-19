<?php
namespace Neuron\Patterns;

use Neuron\Patterns\Singleton;

class Registry extends Singleton\Memory
{
	private $_Objects = [];

	public function __construct()
	{}

	/**
	 * @param $Name
	 * @param $Object
	 */

	public function set( $Name, $Object )
	{
		$this->_Objects[ $Name ] = $Object;
	}

	/**
	 * @param $Name
	 * @return value
	 */

	public function get( $Name )
	{
		if( !array_key_exists( $Name, $this->_Objects ) )
		{
			return null;
		}

		return $this->_Objects[ $Name ];
	}
}
