<?php

class AppMock extends \Neuron\Core\Application\Base
{
	public $Crash     = false;
	public $FailStart = false;

	public function getVersion()
	{
		return '1';
	}

	protected function onRun()
	{
		if( $this->Crash )
		{
			throw new Exception( 'Mock failure.' );
		}
	}

	protected function onStart()
	{
		if( $this->FailStart )
		{
			return false;
		}

		return parent::onStart();
	}

	protected function onError( \Exception $exception )
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
		$this->_App = new AppMock();
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

	public function testSettings()
	{
		$Source = new \Neuron\Setting\Source\Ini( 'examples/test.ini' );

		$this->_App->setSettingSource( $Source );

		$Value = $this->_App->getSetting( 'name', 'test' );

		$this->assertEquals(
			'value',
			$Value
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

	public function testSetSetting()
	{
		$Source = new \Neuron\Setting\Source\Ini( 'examples/test.ini' );

		$this->_App->setSettingSource( $Source );

		$this->_App->setSetting( 'newname', 'value',  'test' );

		$Value = $this->_App->getSetting( 'newname', 'test' );

		$this->assertEquals(
			'value',
			$Value
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
