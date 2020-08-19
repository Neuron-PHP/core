<?php

namespace Neuron\Data\Parser;

class CSV implements IParser
{
	public $_aResults;

	/**
	 * @param $Text
	 * @param array $columns
	 * @return array|bool
	 */

	public function parse($Text, $columns = array() )
	{
		$aResults = array();

		$aData = str_getcsv( $Text );

		$idx = 0;

		foreach($columns as $sColumn )
		{
			$aResults[ $sColumn ] = $aData[ $idx ];
			++$idx;
		}

		if( count( $columns ) != count( $aData ) )
		{
			$this->_aResults = $aResults;
			return false;
		}

		return $aResults;
	}
}

