<?php

namespace Neuron\Patterns\Singleton;

/**
 * Class Base
 * @package Neuron\Patterns\Singleton
 */

abstract class Base implements ISingleton
{
	/**
	 * @return null
	 */

	public static function getInstance()
	{
		if( static::instance() )
		{
			$instance = static::instance();
			if( $instance instanceof self )
			{
				return static::instance();
			}
		}
		else
		{
			$sClass = get_called_class();

			$obj = new $sClass;
			$obj->serialize();
			return $obj;
		}

		return null;
	}
}
