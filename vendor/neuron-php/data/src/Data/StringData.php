<?php

namespace Neuron\Data;

class StringData
{
	public $Value;

	/**
	 * StringData constructor.
	 * @param $String
	 */
	public function __construct( $String )
	{
		$this->Value = $String;
	}

	/**
	 * @return int
	 */
	public function length()
	{
		return strlen( $this->Value );
	}

	/**
	 * @param $Length
	 * @return string
	 */
	public function left( $Length )
	{
		return $this->mid( 0, $Length - 1 );
	}

	/**
	 * @param $Length
	 * @return string
	 */
	public function right( $Length )
	{
		return $this->mid( $this->length() - $Length, $this->length() );
	}

	/**
	 * @param $Start
	 * @param $End
	 * @return string
	 */
	public function mid( $Start, $End )
	{
		return substr( $this->Value, $Start, $End - $Start + 1 );
	}

	/**
	 * @return string
	 */
	public function trim()
	{
		return trim( $this->Value );
	}

	/**
	 * @return string
	 */
	public function deQuote()
	{
		return trim( $this->Value, '"' );
	}

	/**
	 * @return string
	 */
	public function quote()
	{
		return '"'.$this->trim().'"';
	}

	/**
	 * @param bool $CapitalizeFirst
	 * @return mixed|string
	 */
	public function toCamelCase( bool $CapitalizeFirst = true )
	{
		$Str = str_replace('_', '', ucwords( $this->Value, '_'));

		if( !$CapitalizeFirst )
		{
			$Str = lcfirst( $Str );
		}

		return $Str;
	}

	/**
	 * @return string
	 */
	public function toSnakeCase()
	{
		return strtolower( preg_replace('/(?<!^)[A-Z]/', '_$0', $this->Value ) );
	}
}
