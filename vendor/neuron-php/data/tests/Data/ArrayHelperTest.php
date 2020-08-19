<?php

/**
 * Created by PhpStorm.
 * User: lee
 * Date: 8/2/16
 * Time: 4:13 PM
 */

use Neuron\Data\ArrayHelper;

class ArrayHelperTest extends PHPUnit\Framework\TestCase
{
	public function testContains()
	{
		$aTest = [
			'one',
			'two',
			'three'
		];

		// sad
		$this->assertFalse( ArrayHelper::contains( $aTest, 'twenty' ) );

		// happy
		$this->assertTrue( ArrayHelper::contains( $aTest, 'two' ) );
	}

	public function testHasKey()
	{
		$aTest = [
			'one' => 1,
			'two' => 2,
			'three' => 3
		];

		// sad
		$this->assertFalse( ArrayHelper::hasKey( $aTest, 'four' ) );

		// happy
		$this->assertTrue( ArrayHelper::hasKey( $aTest, 'two' ) );
	}

	public function testGetElement()
	{
		$aTest = [
			'one'   => 1,
			'two'   => 2,
			'three' => 3
		];

		// sad
		$this->assertEquals( null, ArrayHelper::getElement( $aTest, 'five' ) );

		// happy
		$this->assertEquals( 1,    ArrayHelper::getElement( $aTest, 'one' ) );
		$this->assertEquals( 20,   ArrayHelper::getElement( $aTest, 'five', 20 ) );
	}

	public function testIndexOf()
	{
		$aTest = [
			'one',
			'two',
			'three'
		];

		// sad
		$this->assertEquals( false, ArrayHelper::indexOf( $aTest, 'twelve' ) );

		// happy
		$this->assertEquals( 1, ArrayHelper::indexOf( $aTest, 'two' ) );
	}

	public function testRemove()
	{
		$aTest = [
			'one',
			'two',
			'three'
		];

		// sad
		$this->assertEquals( false, ArrayHelper::remove( $aTest, 'twelve' ) );

		// happy
		$this->assertEquals( true, ArrayHelper::remove( $aTest, 'two' ) );
		$this->assertEquals( false, ArrayHelper::contains( $aTest, 'two' ) );
	}
}
