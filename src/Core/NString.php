<?php

namespace Neuron\Core;

/**
 * Enhanced string manipulation utility class for the Neuron framework.
 * 
 * This class provides a comprehensive set of string manipulation methods
 * with an object-oriented approach, offering convenient operations for
 * string processing, formatting, and transformation. It wraps PHP's
 * native string functions with additional functionality and consistent behavior.
 * 
 * Key features:
 * - String extraction methods (left, right, mid)
 * - Case conversion utilities (camelCase, snake_case)  
 * - String formatting and cleaning operations
 * - Length and position-based operations
 * - Quote handling for string literals
 * - Modern PHP property syntax with getter/setter
 * 
 * @package Neuron\Core
 * 
 * @example
 * ```php
 * // String manipulation examples
 * $str = new NString('hello_world_example');
 *
 * // Case conversions
 * echo $str->toPascalCase();    // 'HelloWorldExample'
 * echo $str->toCamelCase();     // 'helloWorldExample'
 * echo $str->toSnakeCase();     // 'hello_world_example'
 * echo $str->toUpper();         // 'HELLO_WORLD_EXAMPLE'
 * echo $str->toLower();         // 'hello_world_example'
 *
 * // String extraction
 * echo $str->left(5);           // 'hello'
 * echo $str->right(7);          // 'example'
 * echo $str->mid(6, 10);        // 'world'
 *
 * // String formatting
 * $quoted = new NString('"some text"');
 * echo $quoted->deQuote();      // 'some text'
 * echo $quoted->quote();        // '"some text"'
 * ```
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
	 * @param string $string
	 */

	public function __construct( string $string )
	{
		$this->value = $string;
	}

	/**
	 * @return int
	 */

	public function length(): int
	{
		return strlen( $this->value );
	}

	/**
	 * @param int $length
	 * @return string
	 */

	public function left( int $length ): string
	{
		return $this->mid( 0, $length - 1 );
	}

	/**
	 * @param int $length
	 * @return string
	 */

	public function right( int $length ): string
	{
		return $this->mid( $this->length() - $length, $this->length() );
	}

	/**
	 * @param int $start
	 * @param int $end
	 * @return string
	 */

	public function mid( int $start, int $end ): string
	{
		return substr( $this->value, $start, $end - $start + 1 );
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
	 * Convert string to camelCase (first letter lowercase).
	 *
	 * @return string
	 */
	public function toCamelCase(): string
	{
		$str = str_replace('_', '', ucwords( $this->value, '_'));
		return lcfirst( $str );
	}

	/**
	 * Convert string to PascalCase (first letter uppercase).
	 *
	 * @return string
	 */
	public function toPascalCase(): string
	{
		return str_replace('_', '', ucwords( $this->value, '_'));
	}

	/**
	 * @return string
	 */

	public function toSnakeCase(): string
	{
		return strtolower( preg_replace('/(?<!^)[A-Z]/', '_$0', $this->value ) );
	}

	/**
	 * Convert the string to uppercase.
	 *
	 * @return string
	 */
	public function toUpper(): string
	{
		return strtoupper( $this->value );
	}

	/**
	 * Convert the string to lowercase.
	 *
	 * @return string
	 */
	public function toLower(): string
	{
		return strtolower( $this->value );
	}
}
