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
	 * @param array $Array
	 */
	public function __construct( array $Array = [] )
	{
		$this->value = $Array;
	}

	/**
	 * Check if the array contains a specific value, optionally at a specific key.
	 * 
	 * @param mixed $Value The value to search for
	 * @param string|int|null $Key Optional key to check for the value
	 * @return bool True if the value is found, false otherwise
	 */
	public function contains( mixed $Value, string|int|null $Key = null ): bool
	{
		if( !$Key )
		{
			return in_array( $Value, $this->value );
		}

		if( !$this->hasKey( $Key ) )
		{
			return false;
		}

		return $this->value[ $Key ] == $Value;
	}

	/**
	 * Check if a key exists in the array.
	 * 
	 * @param string|int $Key The key to check for
	 * @return bool True if the key exists, false otherwise
	 */
	public function hasKey( string|int $Key ): bool
	{
		return isset( $this->value[ $Key ] ) || array_key_exists( $Key, $this->value );
	}

	/**
	 * Get an element from the array with optional default value.
	 * 
	 * @param string|int $Key The key to retrieve
	 * @param mixed $Default Default value if key doesn't exist
	 * @return mixed The value at the key or the default value
	 */
	public function getElement( string|int $Key, mixed $Default = null ): mixed
	{
		if( array_key_exists( $Key, $this->value ) )
		{
			return $this->value[ $Key ];
		}

		return $Default;
	}

	/**
	 * Find the index of an element in the array.
	 * 
	 * @param mixed $Item The item to search for
	 * @return int|string|false The key of the item or false if not found
	 */
	public function indexOf( mixed $Item ): int|string|false
	{
		return array_search( $Item, $this->value );
	}

	/**
	 * Remove an element from the array by value.
	 * 
	 * @param mixed $Item The item to remove
	 * @return NArray Returns self for method chaining
	 */
	public function remove( mixed $Item ): NArray
	{
		$Index = $this->indexOf( $Item );

		if( $Index !== false )
		{
			$Array = $this->_value;
			unset( $Array[ $Index ] );
			$this->_value = $Array;
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
	 * @param callable $Callback The callback function to apply
	 * @return NArray A new NArray with transformed values
	 */
	public function map( callable $Callback ): NArray
	{
		return new NArray( array_map( $Callback, $this->value ) );
	}

	/**
	 * Filter the array using a callback function.
	 * 
	 * @param callable $Callback The callback function for filtering
	 * @return NArray A new NArray with filtered values
	 */
	public function filter( callable $Callback ): NArray
	{
		return new NArray( array_filter( $this->value, $Callback ) );
	}

	/**
	 * Reduce the array to a single value using a callback function.
	 * 
	 * @param callable $Callback The callback function for reduction
	 * @param mixed $Initial Initial value for the reduction
	 * @return mixed The reduced value
	 */
	public function reduce( callable $Callback, mixed $Initial = null ): mixed
	{
		return array_reduce( $this->value, $Callback, $Initial );
	}

	/**
	 * Execute a callback for each element in the array.
	 * 
	 * @param callable $Callback The callback function to execute
	 * @return NArray Returns self for method chaining
	 */
	public function each( callable $Callback ): NArray
	{
		foreach( $this->value as $key => $value )
		{
			$Callback( $value, $key );
		}

		return $this;
	}

	/**
	 * Get the first element of the array.
	 * 
	 * @param mixed $Default Default value if array is empty
	 * @return mixed The first element or default value
	 */
	public function first( mixed $Default = null ): mixed
	{
		if( $this->isEmpty() )
		{
			return $Default;
		}

		$Array = $this->_value;
		return reset( $Array );
	}

	/**
	 * Get the last element of the array.
	 * 
	 * @param mixed $Default Default value if array is empty
	 * @return mixed The last element or default value
	 */
	public function last( mixed $Default = null ): mixed
	{
		if( $this->isEmpty() )
		{
			return $Default;
		}

		$Array = $this->_value;
		return end( $Array );
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
	 * @param array|NArray $Array The array to merge with
	 * @return NArray A new NArray with merged values
	 */
	public function merge( array|NArray $Array ): NArray
	{
		$MergeArray = $Array instanceof NArray ? $Array->value : $Array;
		return new NArray( array_merge( $this->value, $MergeArray ) );
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
	 * @param int $SortFlags Sort flags (SORT_REGULAR, SORT_NUMERIC, etc.)
	 * @return NArray A new NArray with sorted values
	 */
	public function sort( int $SortFlags = SORT_REGULAR ): NArray
	{
		$Sorted = $this->value;
		sort( $Sorted, $SortFlags );
		return new NArray( $Sorted );
	}

	/**
	 * Sort the array by keys and return a new NArray.
	 * 
	 * @param int $SortFlags Sort flags (SORT_REGULAR, SORT_NUMERIC, etc.)
	 * @return NArray A new NArray with sorted keys
	 */
	public function sortKeys( int $SortFlags = SORT_REGULAR ): NArray
	{
		$Sorted = $this->value;
		ksort( $Sorted, $SortFlags );
		return new NArray( $Sorted );
	}

	/**
	 * Reverse the array and return a new NArray.
	 * 
	 * @param bool $PreserveKeys Whether to preserve keys
	 * @return NArray A new NArray with reversed values
	 */
	public function reverse( bool $PreserveKeys = false ): NArray
	{
		return new NArray( array_reverse( $this->value, $PreserveKeys ) );
	}

	/**
	 * Extract a slice of the array.
	 * 
	 * @param int $Offset Starting position
	 * @param int|null $Length Number of elements to extract
	 * @param bool $PreserveKeys Whether to preserve keys
	 * @return NArray A new NArray with the slice
	 */
	public function slice( int $Offset, ?int $Length = null, bool $PreserveKeys = false ): NArray
	{
		return new NArray( array_slice( $this->value, $Offset, $Length, $PreserveKeys ) );
	}

	/**
	 * Split the array into chunks.
	 * 
	 * @param int $Size The size of each chunk
	 * @param bool $PreserveKeys Whether to preserve keys
	 * @return NArray A new NArray containing arrays of chunks
	 */
	public function chunk( int $Size, bool $PreserveKeys = false ): NArray
	{
		return new NArray( array_chunk( $this->value, $Size, $PreserveKeys ) );
	}

	/**
	 * Find the first element matching a callback function.
	 * 
	 * @param callable $Callback The callback function for matching
	 * @param mixed $Default Default value if no match found
	 * @return mixed The first matching element or default value
	 */
	public function find( callable $Callback, mixed $Default = null ): mixed
	{
		foreach( $this->value as $key => $value )
		{
			if( $Callback( $value, $key ) )
			{
				return $value;
			}
		}

		return $Default;
	}

	/**
	 * Find the first element with a specific key-value pair.
	 * 
	 * @param string|int $Key The key to check
	 * @param mixed $Value The value to match
	 * @param mixed $Default Default value if no match found
	 * @return mixed The first matching element or default value
	 */
	public function findBy( string|int $Key, mixed $Value, mixed $Default = null ): mixed
	{
		return $this->find( function( $Item ) use ( $Key, $Value ) {
			return is_array( $Item ) && isset( $Item[ $Key ] ) && $Item[ $Key ] === $Value;
		}, $Default );
	}

	/**
	 * Filter elements by a specific key-value pair.
	 * 
	 * @param string|int $Key The key to check
	 * @param mixed $Value The value to match
	 * @return NArray A new NArray with matching elements
	 */
	public function where( string|int $Key, mixed $Value ): NArray
	{
		return $this->filter( function( $Item ) use ( $Key, $Value ) {
			return is_array( $Item ) && isset( $Item[ $Key ] ) && $Item[ $Key ] === $Value;
		});
	}

	/**
	 * Extract a specific key from each element (for arrays of arrays/objects).
	 * 
	 * @param string|int $Key The key to extract
	 * @return NArray A new NArray with extracted values
	 */
	public function pluck( string|int $Key ): NArray
	{
		return $this->map( function( $Item ) use ( $Key ) {
			if( is_array( $Item ) && isset( $Item[ $Key ] ) )
			{
				return $Item[ $Key ];
			}
			if( is_object( $Item ) && property_exists( $Item, $Key ) )
			{
				return $Item->$Key;
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
		return $this->reduce( function( $Carry, $Item ) {
			return $Carry + ( is_numeric( $Item ) ? $Item : 0 );
		}, 0 );
	}

	/**
	 * Calculate the average of all numeric values in the array.
	 * 
	 * @return int|float|null The average or null if no numeric values
	 */
	public function avg(): int|float|null
	{
		$NumericValues = $this->filter( 'is_numeric' );
		$Count = $NumericValues->count();
		
		return $Count > 0 ? $NumericValues->sum() / $Count : null;
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
	 * @param int $Flags JSON encoding flags
	 * @return string JSON representation of the array
	 */
	public function toJson( int $Flags = 0 ): string
	{
		return json_encode( $this->value, $Flags );
	}

	/**
	 * Join array elements with a string.
	 * 
	 * @param string $Glue The string to join elements with
	 * @return string The joined string
	 */
	public function implode( string $Glue ): string
	{
		return implode( $Glue, $this->value );
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