<?php

class SingletonTest extends \Neuron\Patterns\Singleton\Memory
{
	public $Test;
}

class MemoryTest extends PHPUnit\Framework\TestCase
{
	protected function setUp()
	{
	}

	public function testPass()
	{
		$Test = new SingletonTest();

		$Test->Test = 1;
		$Test->serialize();

		$Test2 = SingletonTest::instance();

		$this->assertEquals(
			$Test->Test,
			$Test2->Test
		);
	}

	protected function tearDown()
	{
	}
}
