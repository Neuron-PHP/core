<?php

namespace Neuron\Core;

/**
 * Enhanced array manipulation utility class for the Neuron framework.
 * 
 * This class provides a comprehensive set of array manipulation methods
 * with an object-oriented approach, offering convenient operations for
 * array processing, filtering, transformation, and analysis. It serves as
 * a modern replacement for the static ArrayHelper class with additional
 * functionality and consistent behavior.
 * 
 * Key features:
 * - Object-oriented array wrapper with property hooks
 * - Safe array element access with default values
 * - Comprehensive transformation methods (map, filter, reduce)
 * - Array operations (merge, diff, intersect, unique)
 * - Extraction and slicing operations
 * - Sorting and ordering capabilities
 * - Collection analysis methods
 * - Fluent interface for method chaining
 * - Modern PHP 8.4+ syntax with property hooks
 * 
 * @package Neuron\Core
 * 
 * @example
 * ```php
 * // Basic array operations
 * $arr = new NArray(['apple', 'banana', 'cherry']);
 * 
 * // Safe element access
 * $first = $arr->getElement(0, 'default');     // 'apple'
 * $missing = $arr->getElement(10, 'none');     // 'none'
 * 
 * // Array transformations
 * $uppercased = $arr->map(fn($item) => strtoupper($item));
 * $filtered = $arr->filter(fn($item) => strlen($item) > 5);
 * 
 * // Method chaining
 * $result = $arr->filter(fn($item) => strlen($item) > 4)
 *               ->map(fn($item) => ucfirst($item))
 *               ->sort();
 * 
 * // Collection operations
 * $users = new NArray([
 *     ['name' => 'Alice', 'age' => 30],
 *     ['name' => 'Bob', 'age' => 25]
 * ]);
 * $names = $users->pluck('name');              // ['Alice', 'Bob']
 * $adult = $users->findBy('age', 25);          // ['name' => 'Bob', 'age' => 25]
 * ```
 */
class NArray
{
	private array $_value;

	public array $value {
		get{
			return $this->_value;
		}
		set( array $value ){
			$this->_value = $value;
		}
	}

	/**
	 * NArray constructor.
	 * @param array $array
	 */
	public function __construct( array $array = [] )
	{
		$this->value = $array;
	}

	/**
	 * Check if the array contains a specific value, optionally at a specific key.
	 *
	 * @param mixed $value The value to search for
	 * @param string|int|null $key Optional key to check for the value
	 * @return bool True if the value is found, false otherwise
	 */
	public function contains( mixed $value, string|int|null $key = null ): bool
	{
		if( !$key )
		{
			return in_array( $value, $this->value );
		}

		if( !$this->hasKey( $key ) )
		{
			return false;
		}

		return $this->value[ $key ] == $value;
	}

	/**
	 * Check if a key exists in the array.
	 *
	 * @param string|int $key The key to check for
	 * @return bool True if the key exists, false otherwise
	 */
	public function hasKey( string|int $key ): bool
	{
		return isset( $this->value[ $key ] ) || array_key_exists( $key, $this->value );
	}

	/**
	 * Get an element from the array with optional default value.
	 *
	 * @param string|int $key The key to retrieve
	 * @param mixed $default Default value if key doesn't exist
	 * @return mixed The value at the key or the default value
	 */
	public function getElement( string|int $key, mixed $default = null ): mixed
	{
		if( array_key_exists( $key, $this->value ) )
		{
			return $this->value[ $key ];
		}

		return $default;
	}

	/**
	 * Find the index of an element in the array.
	 *
	 * @param mixed $item The item to search for
	 * @return int|string|false The key of the item or false if not found
	 */
	public function indexOf( mixed $item ): int|string|false
	{
		return array_search( $item, $this->value );
	}

	/**
	 * Remove an element from the array by value.
	 *
	 * @param mixed $item The item to remove
	 * @return NArray Returns self for method chaining
	 */
	public function remove( mixed $item ): NArray
	{
		$index = $this->indexOf( $item );

		if( $index !== false )
		{
			$array = $this->_value;
			unset( $array[ $index ] );
			$this->_value = $array;
		}

		return $this;
	}

	/**
	 * Get the number of elements in the array.
	 * 
	 * @return int The number of elements
	 */
	public function count(): int
	{
		return count( $this->value );
	}

	/**
	 * Check if the array is empty.
	 * 
	 * @return bool True if the array is empty, false otherwise
	 */
	public function isEmpty(): bool
	{
		return empty( $this->value );
	}

	/**
	 * Check if the array is not empty.
	 * 
	 * @return bool True if the array has elements, false otherwise
	 */
	public function isNotEmpty(): bool
	{
		return !$this->isEmpty();
	}

	/**
	 * Apply a callback function to each element and return a new NArray.
	 *
	 * @param callable $callback The callback function to apply
	 * @return NArray A new NArray with transformed values
	 */
	public function map( callable $callback ): NArray
	{
		return new NArray( array_map( $callback, $this->value ) );
	}

	/**
	 * Filter the array using a callback function.
	 *
	 * @param callable $callback The callback function for filtering
	 * @return NArray A new NArray with filtered values
	 */
	public function filter( callable $callback ): NArray
	{
		return new NArray( array_filter( $this->value, $callback ) );
	}

	/**
	 * Reduce the array to a single value using a callback function.
	 *
	 * @param callable $callback The callback function for reduction
	 * @param mixed $initial Initial value for the reduction
	 * @return mixed The reduced value
	 */
	public function reduce( callable $callback, mixed $initial = null ): mixed
	{
		return array_reduce( $this->value, $callback, $initial );
	}

	/**
	 * Execute a callback for each element in the array.
	 *
	 * @param callable $callback The callback function to execute
	 * @return NArray Returns self for method chaining
	 */
	public function each( callable $callback ): NArray
	{
		foreach( $this->value as $key => $value )
		{
			$callback( $value, $key );
		}

		return $this;
	}

	/**
	 * Get the first element of the array.
	 *
	 * @param mixed $default Default value if array is empty
	 * @return mixed The first element or default value
	 */
	public function first( mixed $default = null ): mixed
	{
		if( $this->isEmpty() )
		{
			return $default;
		}

		$array = $this->_value;
		return reset( $array );
	}

	/**
	 * Get the last element of the array.
	 *
	 * @param mixed $default Default value if array is empty
	 * @return mixed The last element or default value
	 */
	public function last( mixed $default = null ): mixed
	{
		if( $this->isEmpty() )
		{
			return $default;
		}

		$array = $this->_value;
		return end( $array );
	}

	/**
	 * Get all keys from the array.
	 * 
	 * @return NArray A new NArray containing the keys
	 */
	public function keys(): NArray
	{
		return new NArray( array_keys( $this->value ) );
	}

	/**
	 * Get all values from the array.
	 * 
	 * @return NArray A new NArray containing the values
	 */
	public function values(): NArray
	{
		return new NArray( array_values( $this->value ) );
	}

	/**
	 * Merge this array with another array or NArray.
	 *
	 * @param array|NArray $array The array to merge with
	 * @return NArray A new NArray with merged values
	 */
	public function merge( array|NArray $array ): NArray
	{
		$mergeArray = $array instanceof NArray ? $array->value : $array;
		return new NArray( array_merge( $this->value, $mergeArray ) );
	}

	/**
	 * Get unique values from the array.
	 * 
	 * @return NArray A new NArray with unique values
	 */
	public function unique(): NArray
	{
		return new NArray( array_unique( $this->value ) );
	}

	/**
	 * Sort the array and return a new NArray.
	 *
	 * @param int $sortFlags Sort flags (SORT_REGULAR, SORT_NUMERIC, etc.)
	 * @return NArray A new NArray with sorted values
	 */
	public function sort( int $sortFlags = SORT_REGULAR ): NArray
	{
		$sorted = $this->value;
		sort( $sorted, $sortFlags );
		return new NArray( $sorted );
	}

	/**
	 * Sort the array by keys and return a new NArray.
	 *
	 * @param int $sortFlags Sort flags (SORT_REGULAR, SORT_NUMERIC, etc.)
	 * @return NArray A new NArray with sorted keys
	 */
	public function sortKeys( int $sortFlags = SORT_REGULAR ): NArray
	{
		$sorted = $this->value;
		ksort( $sorted, $sortFlags );
		return new NArray( $sorted );
	}

	/**
	 * Reverse the array and return a new NArray.
	 *
	 * @param bool $preserveKeys Whether to preserve keys
	 * @return NArray A new NArray with reversed values
	 */
	public function reverse( bool $preserveKeys = false ): NArray
	{
		return new NArray( array_reverse( $this->value, $preserveKeys ) );
	}

	/**
	 * Extract a slice of the array.
	 *
	 * @param int $offset Starting position
	 * @param int|null $length Number of elements to extract
	 * @param bool $preserveKeys Whether to preserve keys
	 * @return NArray A new NArray with the slice
	 */
	public function slice( int $offset, ?int $length = null, bool $preserveKeys = false ): NArray
	{
		return new NArray( array_slice( $this->value, $offset, $length, $preserveKeys ) );
	}

	/**
	 * Split the array into chunks.
	 *
	 * @param int $size The size of each chunk
	 * @param bool $preserveKeys Whether to preserve keys
	 * @return NArray A new NArray containing arrays of chunks
	 */
	public function chunk( int $size, bool $preserveKeys = false ): NArray
	{
		return new NArray( array_chunk( $this->value, $size, $preserveKeys ) );
	}

	/**
	 * Find the first element matching a callback function.
	 *
	 * @param callable $callback The callback function for matching
	 * @param mixed $default Default value if no match found
	 * @return mixed The first matching element or default value
	 */
	public function find( callable $callback, mixed $default = null ): mixed
	{
		foreach( $this->value as $key => $value )
		{
			if( $callback( $value, $key ) )
			{
				return $value;
			}
		}

		return $default;
	}

	/**
	 * Find the first element with a specific key-value pair.
	 *
	 * @param string|int $key The key to check
	 * @param mixed $value The value to match
	 * @param mixed $default Default value if no match found
	 * @return mixed The first matching element or default value
	 */
	public function findBy( string|int $key, mixed $value, mixed $default = null ): mixed
	{
		return $this->find( function( $item ) use ( $key, $value ) {
			return is_array( $item ) && isset( $item[ $key ] ) && $item[ $key ] === $value;
		}, $default );
	}

	/**
	 * Filter elements by a specific key-value pair.
	 *
	 * @param string|int $key The key to check
	 * @param mixed $value The value to match
	 * @return NArray A new NArray with matching elements
	 */
	public function where( string|int $key, mixed $value ): NArray
	{
		return $this->filter( function( $item ) use ( $key, $value ) {
			return is_array( $item ) && isset( $item[ $key ] ) && $item[ $key ] === $value;
		});
	}

	/**
	 * Extract a specific key from each element (for arrays of arrays/objects).
	 *
	 * @param string|int $key The key to extract
	 * @return NArray A new NArray with extracted values
	 */
	public function pluck( string|int $key ): NArray
	{
		return $this->map( function( $item ) use ( $key ) {
			if( is_array( $item ) && isset( $item[ $key ] ) )
			{
				return $item[ $key ];
			}
			if( is_object( $item ) && property_exists( $item, $key ) )
			{
				return $item->$key;
			}
			return null;
		});
	}

	/**
	 * Calculate the sum of all numeric values in the array.
	 * 
	 * @return int|float The sum of all numeric values
	 */
	public function sum(): int|float
	{
		return $this->reduce( function( $carry, $item ) {
			return $carry + ( is_numeric( $item ) ? $item : 0 );
		}, 0 );
	}

	/**
	 * Calculate the average of all numeric values in the array.
	 * 
	 * @return int|float|null The average or null if no numeric values
	 */
	public function avg(): int|float|null
	{
		$numericValues = $this->filter( 'is_numeric' );
		$count = $numericValues->count();

		return $count > 0 ? $numericValues->sum() / $count : null;
	}

	/**
	 * Find the minimum value in the array.
	 * 
	 * @return mixed The minimum value or null if empty
	 */
	public function min(): mixed
	{
		return $this->isEmpty() ? null : min( $this->value );
	}

	/**
	 * Find the maximum value in the array.
	 * 
	 * @return mixed The maximum value or null if empty
	 */
	public function max(): mixed
	{
		return $this->isEmpty() ? null : max( $this->value );
	}

	/**
	 * Convert the array to JSON string.
	 *
	 * @param int $flags JSON encoding flags
	 * @return string JSON representation of the array
	 */
	public function toJson( int $flags = 0 ): string
	{
		return json_encode( $this->value, $flags );
	}

	/**
	 * Join array elements with a string.
	 *
	 * @param string $glue The string to join elements with
	 * @return string The joined string
	 */
	public function implode( string $glue ): string
	{
		return implode( $glue, $this->value );
	}

	/**
	 * Get the raw array value.
	 * 
	 * @return array The internal array
	 */
	public function toArray(): array
	{
		return $this->value;
	}
}