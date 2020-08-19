<?php

/**
 * Class ArrayHelper
 */

namespace Neuron\Data;

class ArrayHelper
{
	/**
	 * @param array $aData
	 * @param $Value
	 * @param $Key
	 * @return bool
	 */

	public static function contains( array $aData, $Value, $Key = null )
	{
		if( !$Key )
		{
			if( in_array( $Value, $aData ) )
			{
				return true;
			}
		}
		else
		{
			foreach( $aData as $Item )
			{
				if( $Item[ $Key ] == $Value )
				{
					return true;
				}
			}
		}
		return false;
	}

	/**
	 * @param array $aData
	 * @param $Key
	 * @return bool
	 */

	public static function hasKey( array $aData, $Key )
	{
		if ( isset( $aData[ $Key ] ) || array_key_exists( $Key, $aData ) )
		{
			return true;
		}

		return false;
	}

	/**
	 * @param array $aData
	 * @param $sKey
	 * @param null $Default
	 * @return mixed|null
	 */

	public static function getElement( array $aData, $sKey, $Default = null )
	{
		if( array_key_exists( $sKey, $aData ) )
		{
			return $aData[ $sKey ];
		}

		if( $Default )
		{
			return $Default;
		}

		return null;
	}

	/**
	 * @param array $aData
	 * @param $Item
	 * @return mixed
	 */

	public static function indexOf( array $aData, $Item )
	{
		return array_search( $Item, $aData );
	}

	/**
	 * @param array $aData
	 * @param $Item
	 * @return bool
	 */

	public static function remove( array &$aData, $Item )
	{
		$Index = self::indexOf( $aData, $Item );

		if( $Index === false )
		{
			return false;
		}

		unset( $aData[ $Index ] );

		return true;
	}
}
