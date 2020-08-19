<?php

class VersionTest extends PHPUnit\Framework\TestCase
{
	public function testGetAsString()
	{
		$Version = new \Neuron\Data\Object\Version();

		$Version->loadFromString(
			"{\"major\":1,\"minor\":2,\"patch\":3}"
		);

		$this->assertEquals(
			'1.2.3',
			$Version->getAsString()
		);

		$Version->loadFromString(
			"{\"major\":1,\"minor\":2,\"patch\":3,\"build\":4}"
		);

		$this->assertEquals(
			'1.2.3 (4)',
			$Version->getAsString()
		);
	}

	public function testLoadFromString()
	{
		$Version = new \Neuron\Data\Object\Version();

		$Version->loadFromString(
			"{\"major\":1,\"minor\":2,\"patch\":3}"
		);

		$this->assertEquals( 1, $Version->Major );
		$this->assertEquals( 2, $Version->Minor );
		$this->assertEquals( 3, $Version->Patch );
	}

	public function testLoadFromStringBuild()
	{
		$Version = new \Neuron\Data\Object\Version();

		$Version->loadFromString(
			"{\"major\":1,\"minor\":2,\"patch\":3,\"build\":4}"
		);

		$this->assertEquals( 1, $Version->Major );
		$this->assertEquals( 2, $Version->Minor );
		$this->assertEquals( 3, $Version->Patch );
		$this->assertEquals( 4, $Version->Build );
	}

	public function testLoadFromFile()
	{
		$Version = new \Neuron\Data\Object\Version();

		$Version->loadFromFile( 'examples/version.json' );

		$this->assertEquals( 1, $Version->Major );
		$this->assertEquals( 2, $Version->Minor );
		$this->assertEquals( 3, $Version->Patch );
	}

	public function testFailLoadFromFile()
	{
		$Version = new \Neuron\Data\Object\Version();

		$Success = true;

		try
		{
			$Version->loadFromFile( 'DoesNotExist' );
		}
		catch( Exception $Exception )
		{
			$Success = false;
		}

		$this->assertFalse( $Success );
	}

	public function testFailLoadFromString()
	{
		$Version = new \Neuron\Data\Object\Version();

		$Pass = false;

		try
		{
			$Version->loadFromString(
				"meh"
			);
		}
		catch( \Exception $exception )
		{
			$Pass = true;
		}

		$this->assertTrue( $Pass );
	}
}
