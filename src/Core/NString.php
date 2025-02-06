<?php

namespace Neuron\Core;

/**
 * String wrapper functions.
 */
class NString
{
	public string $value {
		get{
			return $this->value;
		}
		set( string  $value ){
			$this->value = $value;
		}
	}

	/**
	 * NString constructor.
	 * @param string $String
	 */

	public function __construct( string $String )
	{
		$this->value = $String;
	}

	/**
	 * @return int
	 */

	public function length(): int
	{
		return strlen( $this->value );
	}

	/**
	 * @param int $Length
	 * @return string
	 */

	public function left( int $Length ): string
	{
		return $this->mid( 0, $Length - 1 );
	}

	/**
	 * @param int $Length
	 * @return string
	 */

	public function right( int $Length ): string
	{
		return $this->mid( $this->length() - $Length, $this->length() );
	}

	/**
	 * @param int $Start
	 * @param int $End
	 * @return string
	 */

	public function mid( int $Start, int $End ): string
	{
		return substr( $this->value, $Start, $End - $Start + 1 );
	}

	/**
	 * @return string
	 */

	public function trim(): string
	{
		return trim( $this->value );
	}

	/**
	 * @return string
	 */

	public function deQuote(): string
	{
		return trim( $this->value, '"' );
	}

	/**
	 * @return string
	 */

	public function quote(): string
	{
		return '"'.$this->trim().'"';
	}

	/**
	 * @param bool $CapitalizeFirst
	 * @return mixed|string
	 */

	public function toCamelCase( bool $CapitalizeFirst = true ): mixed
	{
		$Str = str_replace('_', '', ucwords( $this->value, '_'));

		if( !$CapitalizeFirst )
		{
			$Str = lcfirst( $Str );
		}

		return $Str;
	}

	/**
	 * @return string
	 */

	public function toSnakeCase(): string
	{
		return strtolower( preg_replace('/(?<!^)[A-Z]/', '_$0', $this->value ) );
	}
}
