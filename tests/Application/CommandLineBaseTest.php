<?php

namespace Application;

use PHPUnit\Framework\TestCase;
use Tests\TestCommandLine;

class CommandLineBaseTest extends TestCase
{
	public TestCommandLine $CommandLine;

	protected function setUp(): void
	{
		$this->CommandLine = new TestCommandLine( '0.0' );
		parent::setUp();
	}

	public function testRun()
	{
		$this->assertTrue( $this->CommandLine->run() );
	}

	public function testSwitchExit()
	{
		$this->assertFalse( $this->CommandLine->run( [ '--exit' ] ) );
	}

	public function testSwitchWithoutParam()
	{
		$this->assertTrue( $this->CommandLine->run( [ '--poll' ] ) );
	}

	public function testSwitchWithParam()
	{
		$this->CommandLine->run(
			[
				'--interval',
				'10'
			]
		);

		$this->assertEquals(10, $this->CommandLine->Interval );
	}
}
