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

	public $Array;

	protected function setUp(): void
	{
		$this->Array = new NArray( $this::TEST_DATA );

		parent::setUp();
	}

	public function testConstruct()
	{
		$Array = new NArray( $this::TEST_DATA );

		$this->assertEquals( $this::TEST_DATA, $Array->value );
	}

	public function testConstructEmpty()
	{
		$Array = new NArray();

		$this->assertEquals( [], $Array->value );
	}

	public function testValue()
	{
		$this->Array->value = ['test', 'data'];

		$this->assertEquals( ['test', 'data'], $this->Array->value );
	}

	public function testContains()
	{
		$this->assertTrue( $this->Array->contains( 'apple' ) );
		$this->assertFalse( $this->Array->contains( 'grape' ) );
	}

	public function testContainsWithKey()
	{
		$Array = new NArray( $this::ASSOC_DATA );

		$this->assertTrue( $Array->contains( 'John', 'name' ) );
		$this->assertFalse( $Array->contains( 'Jane', 'name' ) );
		$this->assertFalse( $Array->contains( 'John', 'invalid_key' ) );
	}

	public function testHasKey()
	{
		$Array = new NArray( $this::ASSOC_DATA );

		$this->assertTrue( $Array->hasKey( 'name' ) );
		$this->assertFalse( $Array->hasKey( 'invalid_key' ) );
	}

	public function testGetElement()
	{
		$this->assertEquals( 'apple', $this->Array->getElement( 0 ) );
		$this->assertEquals( 'default', $this->Array->getElement( 10, 'default' ) );
		$this->assertNull( $this->Array->getElement( 10 ) );
	}

	public function testIndexOf()
	{
		$this->assertEquals( 1, $this->Array->indexOf( 'banana' ) );
		$this->assertFalse( $this->Array->indexOf( 'grape' ) );
	}

	public function testRemove()
	{
		$Result = $this->Array->remove( 'banana' );

		$this->assertInstanceOf( NArray::class, $Result );
		$this->assertFalse( $this->Array->contains( 'banana' ) );
		$this->assertEquals( 3, $this->Array->count() );
	}

	public function testCount()
	{
		$this->assertEquals( 4, $this->Array->count() );

		$Empty = new NArray();
		$this->assertEquals( 0, $Empty->count() );
	}

	public function testIsEmpty()
	{
		$this->assertFalse( $this->Array->isEmpty() );

		$Empty = new NArray();
		$this->assertTrue( $Empty->isEmpty() );
	}

	public function testIsNotEmpty()
	{
		$this->assertTrue( $this->Array->isNotEmpty() );

		$Empty = new NArray();
		$this->assertFalse( $Empty->isNotEmpty() );
	}

	public function testMap()
	{
		$Result = $this->Array->map( fn($item) => strtoupper($item) );

		$this->assertInstanceOf( NArray::class, $Result );
		$this->assertEquals( ['APPLE', 'BANANA', 'CHERRY', 'DATE'], $Result->value );
		$this->assertEquals( $this::TEST_DATA, $this->Array->value ); // Original unchanged
	}

	public function testFilter()
	{
		$Result = $this->Array->filter( fn($item) => strlen($item) > 5 );

		$this->assertInstanceOf( NArray::class, $Result );
		$this->assertTrue( $Result->contains( 'banana' ) );
		$this->assertTrue( $Result->contains( 'cherry' ) );
		$this->assertFalse( $Result->contains( 'apple' ) );
	}

	public function testReduce()
	{
		$NumericArray = new NArray( $this::NUMERIC_DATA );
		$Sum = $NumericArray->reduce( fn($carry, $item) => $carry + $item, 0 );

		$this->assertEquals( 15, $Sum );
	}

	public function testEach()
	{
		$Count = 0;
		$Result = $this->Array->each( function($item) use (&$Count) {
			$Count++;
		});

		$this->assertInstanceOf( NArray::class, $Result );
		$this->assertEquals( 4, $Count );
	}

	public function testFirst()
	{
		$this->assertEquals( 'apple', $this->Array->first() );

		$Empty = new NArray();
		$this->assertEquals( 'default', $Empty->first( 'default' ) );
		$this->assertNull( $Empty->first() );
	}

	public function testLast()
	{
		$this->assertEquals( 'date', $this->Array->last() );

		$Empty = new NArray();
		$this->assertEquals( 'default', $Empty->last( 'default' ) );
		$this->assertNull( $Empty->last() );
	}

	public function testKeys()
	{
		$Array = new NArray( $this::ASSOC_DATA );
		$Result = $Array->keys();

		$this->assertInstanceOf( NArray::class, $Result );
		$this->assertEquals( ['name', 'age', 'city'], $Result->value );
	}

	public function testValues()
	{
		$Array = new NArray( $this::ASSOC_DATA );
		$Result = $Array->values();

		$this->assertInstanceOf( NArray::class, $Result );
		$this->assertEquals( ['John', 30, 'NYC'], $Result->value );
	}

	public function testMerge()
	{
		$Other = new NArray( ['grape', 'orange'] );
		$Result = $this->Array->merge( $Other );

		$this->assertInstanceOf( NArray::class, $Result );
		$this->assertEquals( 6, $Result->count() );
		$this->assertTrue( $Result->contains( 'apple' ) );
		$this->assertTrue( $Result->contains( 'grape' ) );
	}

	public function testMergeWithArray()
	{
		$Result = $this->Array->merge( ['grape', 'orange'] );

		$this->assertInstanceOf( NArray::class, $Result );
		$this->assertEquals( 6, $Result->count() );
		$this->assertTrue( $Result->contains( 'grape' ) );
	}

	public function testUnique()
	{
		$Duplicates = new NArray( ['apple', 'banana', 'apple', 'cherry'] );
		$Result = $Duplicates->unique();

		$this->assertInstanceOf( NArray::class, $Result );
		$this->assertEquals( 3, $Result->count() );
		$this->assertTrue( $Result->contains( 'apple' ) );
		$this->assertTrue( $Result->contains( 'banana' ) );
		$this->assertTrue( $Result->contains( 'cherry' ) );
	}

	public function testSort()
	{
		$Result = $this->Array->sort();

		$this->assertInstanceOf( NArray::class, $Result );
		$this->assertEquals( ['apple', 'banana', 'cherry', 'date'], $Result->value );
	}

	public function testSortKeys()
	{
		$Array = new NArray( ['c' => 3, 'a' => 1, 'b' => 2] );
		$Result = $Array->sortKeys();

		$this->assertInstanceOf( NArray::class, $Result );
		$this->assertEquals( ['a' => 1, 'b' => 2, 'c' => 3], $Result->value );
	}

	public function testReverse()
	{
		$Result = $this->Array->reverse();

		$this->assertInstanceOf( NArray::class, $Result );
		$this->assertEquals( ['date', 'cherry', 'banana', 'apple'], $Result->value );
	}

	public function testSlice()
	{
		$Result = $this->Array->slice( 1, 2 );

		$this->assertInstanceOf( NArray::class, $Result );
		$this->assertEquals( ['banana', 'cherry'], $Result->value );
	}

	public function testChunk()
	{
		$Result = $this->Array->chunk( 2 );

		$this->assertInstanceOf( NArray::class, $Result );
		$this->assertEquals( 2, $Result->count() );
		$this->assertEquals( ['apple', 'banana'], $Result->getElement( 0 ) );
		$this->assertEquals( ['cherry', 'date'], $Result->getElement( 1 ) );
	}

	public function testFind()
	{
		$Result = $this->Array->find( fn($item) => strlen($item) > 5 );

		$this->assertEquals( 'banana', $Result );

		$NotFound = $this->Array->find( fn($item) => strlen($item) > 10, 'default' );
		$this->assertEquals( 'default', $NotFound );
	}

	public function testFindBy()
	{
		$Users = new NArray( [
			['name' => 'Alice', 'age' => 30],
			['name' => 'Bob', 'age' => 25]
		]);

		$Result = $Users->findBy( 'name', 'Bob' );
		$this->assertEquals( ['name' => 'Bob', 'age' => 25], $Result );

		$NotFound = $Users->findBy( 'name', 'Charlie', 'default' );
		$this->assertEquals( 'default', $NotFound );
	}

	public function testWhere()
	{
		$Users = new NArray( [
			['name' => 'Alice', 'age' => 30],
			['name' => 'Bob', 'age' => 25],
			['name' => 'Charlie', 'age' => 30]
		]);

		$Result = $Users->where( 'age', 30 );

		$this->assertInstanceOf( NArray::class, $Result );
		$this->assertEquals( 2, $Result->count() );
	}

	public function testPluck()
	{
		$Users = new NArray( [
			['name' => 'Alice', 'age' => 30],
			['name' => 'Bob', 'age' => 25]
		]);

		$Result = $Users->pluck( 'name' );

		$this->assertInstanceOf( NArray::class, $Result );
		$this->assertEquals( ['Alice', 'Bob'], $Result->value );
	}

	public function testSum()
	{
		$NumericArray = new NArray( $this::NUMERIC_DATA );
		$this->assertEquals( 15, $NumericArray->sum() );

		$MixedArray = new NArray( [1, 'text', 3, 'more text', 5] );
		$this->assertEquals( 9, $MixedArray->sum() );
	}

	public function testAvg()
	{
		$NumericArray = new NArray( $this::NUMERIC_DATA );
		$this->assertEquals( 3, $NumericArray->avg() );

		$EmptyArray = new NArray();
		$this->assertNull( $EmptyArray->avg() );
	}

	public function testMin()
	{
		$NumericArray = new NArray( $this::NUMERIC_DATA );
		$this->assertEquals( 1, $NumericArray->min() );

		$EmptyArray = new NArray();
		$this->assertNull( $EmptyArray->min() );
	}

	public function testMax()
	{
		$NumericArray = new NArray( $this::NUMERIC_DATA );
		$this->assertEquals( 5, $NumericArray->max() );

		$EmptyArray = new NArray();
		$this->assertNull( $EmptyArray->max() );
	}

	public function testToJson()
	{
		$Expected = json_encode( $this::TEST_DATA );
		$this->assertEquals( $Expected, $this->Array->toJson() );
	}

	public function testImplode()
	{
		$this->assertEquals( 'apple,banana,cherry,date', $this->Array->implode( ',' ) );
	}

	public function testToArray()
	{
		$this->assertEquals( $this::TEST_DATA, $this->Array->toArray() );
	}

	public function testMethodChaining()
	{
		// TEST_DATA = ['apple', 'banana', 'cherry', 'date']
		// Filter for items > 4 chars should get: banana(6), cherry(6)
		// apple(5) and date(4) should be excluded
		$Result = $this->Array
			->filter( fn($item) => strlen($item) > 5 )  // Only banana and cherry
			->map( fn($item) => strtoupper($item) )
			->values()  // Reset keys to 0, 1, 2...
			->sort();

		$this->assertInstanceOf( NArray::class, $Result );
		$this->assertEquals( ['BANANA', 'CHERRY'], $Result->value );
	}
}