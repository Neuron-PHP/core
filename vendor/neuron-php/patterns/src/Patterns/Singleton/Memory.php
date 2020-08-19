<?php

namespace Neuron\Patterns\Singleton;

class Memory extends Base
{
	static $_instance = [];

	public function serialize()
	{
		static::$_instance[ get_called_class() ] = $this;
	}

	public static function invalidate()
	{
		static::$_instance[ get_called_class() ] = false;
	}

	public static function instance()
	{
		if( array_key_exists( get_called_class(), static::$_instance ) )
		{
			return static::$_instance[ get_called_class() ];
		}
		else
		{
			return null;
		}
	}
}
