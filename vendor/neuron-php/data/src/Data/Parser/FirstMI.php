<?php

namespace Neuron\Data\Parser;


class FirstMI implements IParser
{
	/**
	 * Parses First M
	 *
	 * @param $Text
	 * @param array $Locations
	 * @return array first, middle, last
	 *
	 * @SuppressWarnings(PHPMD)
	 */

	public function parse( $Text, $Locations = array() )
	{
		$Text  = str_replace( '.', '', $Text );
		$Parts = explode( ' ', $Text );

		$Middle = '';

		if( count( $Parts ) > 1 )
		{
			$Parts[ 1 ] = trim( $Parts[ 1 ] );

			if( strlen( $Parts[ 1 ] ) == 1 )
			{
				$Middle = $Parts[ 1 ];
			}
		}

		$First = trim( $Parts[ 0 ] );

		return [ $First, $Middle ];
	}
}
