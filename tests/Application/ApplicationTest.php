<?php

class AppMock extends \Neuron\Core\Application\Base
{
	public $Crash     = false;
	public $FailStart = false;

	protected function onRun()
	{
		if( $this->Crash )
		{
			throw new Exception( 'Mock failure.' );
		}
	}

	protected function onStart() : bool
	{
		if( $this->FailStart )
		{
			return false;
		}

		return parent::onStart();
	}

	protected function onError( \Exception $exception ) : bool
	{
		parent::onError( $exception );

		return false;
	}
}

class ApplicationTest extends PHPUnit\Framework\TestCase
{
	private $_App;

	public function setup()
	{
		$this->_App = new AppMock( "1.0" );
	}

	public function testRun()
	{
		$this->assertTrue( $this->_App->run() );
	}

	public function testRegistry()
	{
		$this->_App->setRegistryObject( 'test', '1234' );

		$result = $this->_App->getRegistryObject( 'test' );

		$this->assertEquals(
			$result,
			'1234'
		);
	}

	public function testIsCommandLine()
	{
		$this->assertTrue(
			$this->_App->isCommandLine()
		);
	}

	public function testOnError()
	{
		$this->_App->Crash = true;

		$this->assertEquals(
			false,
			$this->_App->run()
		);
	}

	public function testGetParameter()
	{
		$this->_App->run(
			[
				'test' => 'monkey'
			]
		);

		$this->assertEquals(
			'monkey',
			$this->_App->getParameter( 'test' )
		);
	}

	public function testStart()
	{
		$this->_App->FailStart = true;

		$this->assertFalse(
			$this->_App->run()
		);
	}

	public function testGetLogger()
	{
		$this->assertEquals(
			get_class( $this->_App->getLogger() ),
			\Neuron\Log\LogMux::class
		);
	}

	public function testGetParameters()
	{
		$this->_App->run( [ 'test' => 'test' ] );
		$this->assertTrue(
			is_array( $this->_App->getParameters() )
		);
	}
}
