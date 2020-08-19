<?php

namespace Neuron\Data\Parser;


class LastFirstMI implements IParser
{
	/**
	 * Parses Last, First M
	 *
	 * @param $Text
	 * @param array $Locations
	 * @return array first, middle, last
	 *
	 * @SuppressWarnings(PHPMD)
	 */

	public function parse($Text, $Locations = array() )
	{
		$aName = explode( ',', $Text );

		$sFirst  = trim( $aName[ 1 ] );
		$sMiddle = '';

		$aFirstMiddle = explode( ' ', $sFirst );

		if( count( $aFirstMiddle ) > 1 )
		{
			$aFirstMiddle[ 1 ] = trim( $aFirstMiddle[ 1 ] );

			if( strlen( $aFirstMiddle[ 1 ] ) == 1 )
			{
				$sFirst  = $aFirstMiddle[ 0 ];
				$sMiddle = $aFirstMiddle[ 1 ];
			}
		}

		$sLast = trim( $aName[ 0 ] );

		return [ $sFirst, $sMiddle, $sLast ];
	}
}
