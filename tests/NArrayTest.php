<?php

namespace Tests;
use Neuron\Core\NArray;
use PHPUnit;

/**
 * Unit tests for NArray class.
 * 
 * Tests comprehensive array manipulation functionality including
 * element access, transformations, filtering, sorting, and aggregation.
 */
class NArrayTest extends PHPUnit\Framework\TestCase
{
	const TEST_DATA = ['apple', 'banana', 'cherry', 'date'];
	const NUMERIC_DATA = [1, 2, 3, 4, 5];
	const ASSOC_DATA = ['name' => 'John', 'age' => 30, 'city' => 'NYC'];

	public $array;

	protected function setUp(): void
	{
		$this->array = new NArray( $this::TEST_DATA );

		parent::setUp();
	}

	public function testConstruct()
	{
		$array = new NArray( $this::TEST_DATA );

		$this->assertEquals( $this::TEST_DATA, $array->value );
	}

	public function testConstructEmpty()
	{
		$array = new NArray();

		$this->assertEquals( [], $array->value );
	}

	public function testValue()
	{
		$this->array->value = ['test', 'data'];

		$this->assertEquals( ['test', 'data'], $this->array->value );
	}

	public function testContains()
	{
		$this->assertTrue( $this->array->contains( 'apple' ) );
		$this->assertFalse( $this->array->contains( 'grape' ) );
	}

	public function testContainsWithKey()
	{
		$array = new NArray( $this::ASSOC_DATA );

		$this->assertTrue( $array->contains( 'John', 'name' ) );
		$this->assertFalse( $array->contains( 'Jane', 'name' ) );
		$this->assertFalse( $array->contains( 'John', 'invalid_key' ) );
	}

	public function testHasKey()
	{
		$array = new NArray( $this::ASSOC_DATA );

		$this->assertTrue( $array->hasKey( 'name' ) );
		$this->assertFalse( $array->hasKey( 'invalid_key' ) );
	}

	public function testGetElement()
	{
		$this->assertEquals( 'apple', $this->array->getElement( 0 ) );
		$this->assertEquals( 'default', $this->array->getElement( 10, 'default' ) );
		$this->assertNull( $this->array->getElement( 10 ) );
	}

	public function testIndexOf()
	{
		$this->assertEquals( 1, $this->array->indexOf( 'banana' ) );
		$this->assertFalse( $this->array->indexOf( 'grape' ) );
	}

	public function testRemove()
	{
		$result = $this->array->remove( 'banana' );

		$this->assertInstanceOf( NArray::class, $result );
		$this->assertFalse( $this->array->contains( 'banana' ) );
		$this->assertEquals( 3, $this->array->count() );
	}

	public function testCount()
	{
		$this->assertEquals( 4, $this->array->count() );

		$empty = new NArray();
		$this->assertEquals( 0, $empty->count() );
	}

	public function testIsEmpty()
	{
		$this->assertFalse( $this->array->isEmpty() );

		$empty = new NArray();
		$this->assertTrue( $empty->isEmpty() );
	}

	public function testIsNotEmpty()
	{
		$this->assertTrue( $this->array->isNotEmpty() );

		$empty = new NArray();
		$this->assertFalse( $empty->isNotEmpty() );
	}

	public function testMap()
	{
		$result = $this->array->map( fn($item) => strtoupper($item) );

		$this->assertInstanceOf( NArray::class, $result );
		$this->assertEquals( ['APPLE', 'BANANA', 'CHERRY', 'DATE'], $result->value );
		$this->assertEquals( $this::TEST_DATA, $this->array->value ); // Original unchanged
	}

	public function testFilter()
	{
		$result = $this->array->filter( fn($item) => strlen($item) > 5 );

		$this->assertInstanceOf( NArray::class, $result );
		$this->assertTrue( $result->contains( 'banana' ) );
		$this->assertTrue( $result->contains( 'cherry' ) );
		$this->assertFalse( $result->contains( 'apple' ) );
	}

	public function testReduce()
	{
		$numericArray = new NArray( $this::NUMERIC_DATA );
		$sum = $numericArray->reduce( fn($carry, $item) => $carry + $item, 0 );

		$this->assertEquals( 15, $sum );
	}

	public function testEach()
	{
		$count = 0;
		$result = $this->array->each( function($item) use (&$count) {
			$count++;
		});

		$this->assertInstanceOf( NArray::class, $result );
		$this->assertEquals( 4, $count );
	}

	public function testFirst()
	{
		$this->assertEquals( 'apple', $this->array->first() );

		$empty = new NArray();
		$this->assertEquals( 'default', $empty->first( 'default' ) );
		$this->assertNull( $empty->first() );
	}

	public function testLast()
	{
		$this->assertEquals( 'date', $this->array->last() );

		$empty = new NArray();
		$this->assertEquals( 'default', $empty->last( 'default' ) );
		$this->assertNull( $empty->last() );
	}

	public function testKeys()
	{
		$array = new NArray( $this::ASSOC_DATA );
		$result = $array->keys();

		$this->assertInstanceOf( NArray::class, $result );
		$this->assertEquals( ['name', 'age', 'city'], $result->value );
	}

	public function testValues()
	{
		$array = new NArray( $this::ASSOC_DATA );
		$result = $array->values();

		$this->assertInstanceOf( NArray::class, $result );
		$this->assertEquals( ['John', 30, 'NYC'], $result->value );
	}

	public function testMerge()
	{
		$other = new NArray( ['grape', 'orange'] );
		$result = $this->array->merge( $other );

		$this->assertInstanceOf( NArray::class, $result );
		$this->assertEquals( 6, $result->count() );
		$this->assertTrue( $result->contains( 'apple' ) );
		$this->assertTrue( $result->contains( 'grape' ) );
	}

	public function testMergeWithArray()
	{
		$result = $this->array->merge( ['grape', 'orange'] );

		$this->assertInstanceOf( NArray::class, $result );
		$this->assertEquals( 6, $result->count() );
		$this->assertTrue( $result->contains( 'grape' ) );
	}

	public function testUnique()
	{
		$duplicates = new NArray( ['apple', 'banana', 'apple', 'cherry'] );
		$result = $duplicates->unique();

		$this->assertInstanceOf( NArray::class, $result );
		$this->assertEquals( 3, $result->count() );
		$this->assertTrue( $result->contains( 'apple' ) );
		$this->assertTrue( $result->contains( 'banana' ) );
		$this->assertTrue( $result->contains( 'cherry' ) );
	}

	public function testSort()
	{
		$result = $this->array->sort();

		$this->assertInstanceOf( NArray::class, $result );
		$this->assertEquals( ['apple', 'banana', 'cherry', 'date'], $result->value );
	}

	public function testSortKeys()
	{
		$array = new NArray( ['c' => 3, 'a' => 1, 'b' => 2] );
		$result = $array->sortKeys();

		$this->assertInstanceOf( NArray::class, $result );
		$this->assertEquals( ['a' => 1, 'b' => 2, 'c' => 3], $result->value );
	}

	public function testReverse()
	{
		$result = $this->array->reverse();

		$this->assertInstanceOf( NArray::class, $result );
		$this->assertEquals( ['date', 'cherry', 'banana', 'apple'], $result->value );
	}

	public function testSlice()
	{
		$result = $this->array->slice( 1, 2 );

		$this->assertInstanceOf( NArray::class, $result );
		$this->assertEquals( ['banana', 'cherry'], $result->value );
	}

	public function testChunk()
	{
		$result = $this->array->chunk( 2 );

		$this->assertInstanceOf( NArray::class, $result );
		$this->assertEquals( 2, $result->count() );
		$this->assertEquals( ['apple', 'banana'], $result->getElement( 0 ) );
		$this->assertEquals( ['cherry', 'date'], $result->getElement( 1 ) );
	}

	public function testFind()
	{
		$result = $this->array->find( fn($item) => strlen($item) > 5 );

		$this->assertEquals( 'banana', $result );

		$notFound = $this->array->find( fn($item) => strlen($item) > 10, 'default' );
		$this->assertEquals( 'default', $notFound );
	}

	public function testFindBy()
	{
		$users = new NArray( [
			['name' => 'Alice', 'age' => 30],
			['name' => 'Bob', 'age' => 25]
		]);

		$result = $users->findBy( 'name', 'Bob' );
		$this->assertEquals( ['name' => 'Bob', 'age' => 25], $result );

		$notFound = $users->findBy( 'name', 'Charlie', 'default' );
		$this->assertEquals( 'default', $notFound );
	}

	public function testWhere()
	{
		$users = new NArray( [
			['name' => 'Alice', 'age' => 30],
			['name' => 'Bob', 'age' => 25],
			['name' => 'Charlie', 'age' => 30]
		]);

		$result = $users->where( 'age', 30 );

		$this->assertInstanceOf( NArray::class, $result );
		$this->assertEquals( 2, $result->count() );
	}

	public function testPluck()
	{
		$users = new NArray( [
			['name' => 'Alice', 'age' => 30],
			['name' => 'Bob', 'age' => 25]
		]);

		$result = $users->pluck( 'name' );

		$this->assertInstanceOf( NArray::class, $result );
		$this->assertEquals( ['Alice', 'Bob'], $result->value );
	}

	public function testSum()
	{
		$numericArray = new NArray( $this::NUMERIC_DATA );
		$this->assertEquals( 15, $numericArray->sum() );

		$mixedArray = new NArray( [1, 'text', 3, 'more text', 5] );
		$this->assertEquals( 9, $mixedArray->sum() );
	}

	public function testAvg()
	{
		$numericArray = new NArray( $this::NUMERIC_DATA );
		$this->assertEquals( 3, $numericArray->avg() );

		$emptyArray = new NArray();
		$this->assertNull( $emptyArray->avg() );
	}

	public function testMin()
	{
		$numericArray = new NArray( $this::NUMERIC_DATA );
		$this->assertEquals( 1, $numericArray->min() );

		$emptyArray = new NArray();
		$this->assertNull( $emptyArray->min() );
	}

	public function testMax()
	{
		$numericArray = new NArray( $this::NUMERIC_DATA );
		$this->assertEquals( 5, $numericArray->max() );

		$emptyArray = new NArray();
		$this->assertNull( $emptyArray->max() );
	}

	public function testToJson()
	{
		$expected = json_encode( $this::TEST_DATA );
		$this->assertEquals( $expected, $this->array->toJson() );
	}

	public function testImplode()
	{
		$this->assertEquals( 'apple,banana,cherry,date', $this->array->implode( ',' ) );
	}

	public function testToArray()
	{
		$this->assertEquals( $this::TEST_DATA, $this->array->toArray() );
	}

	public function testMethodChaining()
	{
		// TEST_DATA = ['apple', 'banana', 'cherry', 'date']
		// Filter for items > 4 chars should get: banana(6), cherry(6)
		// apple(5) and date(4) should be excluded
		$result = $this->array
			->filter( fn($item) => strlen($item) > 5 )  // Only banana and cherry
			->map( fn($item) => strtoupper($item) )
			->values()  // Reset keys to 0, 1, 2...
			->sort();

		$this->assertInstanceOf( NArray::class, $result );
		$this->assertEquals( ['BANANA', 'CHERRY'], $result->value );
	}
}