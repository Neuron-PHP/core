<?php

namespace Neuron\Data;

class Formatter
{
	/**
	 * @param array $Parts
	 * @return bool
	 */
	public static function ddmmyyyy( array $Parts )
	{
		$Match = true;

		if( strlen( $Parts[ 0 ] ) > 2 ||
			strlen( $Parts[ 1 ] ) > 2 ||
			strlen( $Parts[ 2 ] ) != 4 )
		{
			$Match = false;
		}

		if( $Parts[ 0 ] > 31 )
		{
			$Match = false;
		}

		if( $Parts[ 1 ] > 12 )
		{
			$Match = false;
		}

		return $Match;
	}

	/**
	 * @param array $Parts
	 * @return bool
	 */
	public static function mmddyyyy( array $Parts )
	{
		$Match = true;

		if( strlen( $Parts[ 0 ] ) > 2 ||
			strlen( $Parts[ 1 ] ) > 2 ||
			strlen( $Parts[ 2 ] ) != 4 )
		{
			$Match = false;
		}

		if( $Parts[ 0 ] > 12 )
		{
			$Match = false;
		}

		if( $Parts[ 1 ] > 31 )
		{
			$Match = false;
		}

		return $Match;
	}

	/**
	 * @param array $Parts
	 * @return bool
	 */
	public static function yyyymmdd( array $Parts )
	{
		$Match = true;

		if( strlen( $Parts[ 0 ] ) != 4 ||
			strlen( $Parts[ 1 ] ) > 2 ||
			strlen( $Parts[ 2 ] ) > 2 )
		{
			$Match = false;
		}

		if( $Parts[ 1 ] > 12 )
		{
			$Match = false;
		}

		if( $Parts[ 2 ] > 31 )
		{
			$Match = false;
		}

		return $Match;
	}

	/**
	 * Tries to figure out which format the date is in then translates it to yyyy-mm-dd
	 * Handles -, / and . as delimiters.
	 * Takes dd/mm/yyyy, mm/dd/yyyy or yyyy/mm/dd
	 *
	 * @param string $Date
	 * @return string
	 */
	public static function normalizeDate( string $Date ) : string
	{
		$Date = trim( $Date );
		$Parts = explode( ' ', $Date );

		if( $Parts )
		{
			$Date = $Parts[ 0 ];
		}

		$Delimiter = '';

		$Separators = [ '-', '/', '.' ];

		foreach( $Separators as $Separator )
		{
			if( strstr( $Date, $Separator ) )
			{
				$Delimiter = $Separator;
			}
		}

		if( !$Delimiter )
		{
			return false;
		}

		$Parts = explode( $Delimiter, $Date );

		if( count( $Parts ) != 3 )
		{
			return false;
		}

		if( self::yyyymmdd( $Parts ) )
		{
			return $Parts[ 0 ].'-'.$Parts[ 1 ].'-'.$Parts[ 2 ];
		}

		if( self::mmddyyyy( $Parts ) )
		{
			return $Parts[ 2 ].'-'.$Parts[ 0 ].'-'.$Parts[ 1 ];
		}

		if( self::ddmmyyyy( $Parts ) )
		{
			return $Parts[ 2 ].'-'.$Parts[ 1 ].'-'.$Parts[ 0 ];
		}

		return '';
	}

	/**
	 * Format currency to n places.
	 * @param $Number
	 * @param $Format
	 * @param $PadLength
	 * @return string
	 */

	public static function currency( $Number, string $Format = "%01.2f", $PadLength = 9 )
	{
		return
			"<pre style='display:inline; background-color: inherit; color:white; border: none;'>".
				str_pad(
					sprintf(
						$Format,
						round( $Number, 2 )
					),
					$PadLength,
					'_',
					STR_PAD_LEFT
				).
			"</pre>";
	}

	/**
	 * @param $DateTime
	 * @param string $Format
	 * @return false|string
	 */
	public static function dateTime( $DateTime, string $Format = 'Y-m-d g:i a' )
	{
		$Parts = explode( ' ', $DateTime );
		$Date  = self::normalizeDate( $Parts[ 0 ] );

		$DateTime = $Date.' '.$Parts[ 1 ];

		if( count( $Parts ) > 2 )
		{
			$DateTime .= ' '.$Parts[ 2 ];
		}

		return date( $Format, strtotime( $DateTime ) );
	}

	/**
	 * @param string $Date
	 * @param string $Format
	 * @return false|string
	 */
	public static function dateOnly( string $Date, string $Format = 'Y-m-d' )
	{
		$Date = self::normalizeDate( $Date );
		return date( $Format, strtotime( $Date ) );
	}

	/**
	 * @param string $Time
	 * @param string $Format
	 * @return false|string
	 */
	public static function timeOnly( string $Time, string $Format = 'g:i a' )
	{
		return date( $Format, strtotime( $Time ) );
	}

	public static function phoneNumber( string $Phone ) : string
	{
		$Phone = preg_replace("/[^0-9]/", "", $Phone );

		if( strlen( $Phone ) == 7 )
		{
			$Pre   = substr( $Phone, 0, 3 );
			$Post  = substr( $Phone, 3, 4 );
			$Phone = "$Pre-$Post";
		}
		else if( strlen( $Phone ) == 10 )
		{
			$Area  = substr( $Phone, 0, 3 );
			$Pre   = substr( $Phone, 3, 3 );
			$Post  = substr( $Phone, 6, 4 );
			$Phone = "($Area) $Pre-$Post";
		}

		return $Phone;
	}
}
