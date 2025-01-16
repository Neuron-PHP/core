<?php
namespace Tests\Application;

use Exception;
use Neuron\Data\Setting\Source\Ini;
use Neuron\Patterns\Registry;
use PHPUnit\Framework\TestCase;
use Tests\AppMock;
use Tests\TestListener;

class ApplicationTest extends TestCase
{
	private $_App;

	protected function setUp(): void
	{
		parent::setUp();

		$SettingSource = new Ini( 'examples/config/application.ini' );
		$this->_App = new AppMock( "1.0", $SettingSource );
	}

	public function testEventListenerPath()
	{
		$this->_App->setEventListenersPath( 'examples/Listeners' );
		$this->assertEquals(
			'examples/Listeners',
			$this->_App->getEventListenersPath()
		);
	}

	public function testGetVersion()
	{
		$this->assertEquals(
			'1.0',
			$this->_App->getVersion()
		);
	}

	public function testSetSettingSource()
	{
		$SettingSource = new Ini( 'examples/config/application.ini' );

		$this->assertNotNull(
			$this->_App->setSettingSource( $SettingSource )
		);
	}

	public function testSetSetting()
	{
		$this->_App->setSetting( 'name', 'value', 'section' );

		$this->assertEquals(
			'value',
			$this->_App->getSetting( 'name', 'section' )
		);
	}

	public function testNoConfig()
	{
		$App = new AppMock( "1.0" );
		$this->assertNull( $App->getSetting( "test", "test" ) );
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

	public function testFatal()
	{
		$this->_App->setHandleFatal( true );
		$this->_App->run();
		$this->_App->crash();

		$this->assertTrue(
			$this->_App->DidCrash
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
		$this->_App->setHandleErrors( true );
		$this->_App->Error = true;

		$this->_App->run();

		$this->assertTrue(
			$this->_App->DidError
		);
	}

	public function testCrash()
	{
		$this->_App->setHandleFatal( true );

		$this->_App->Crash = true;
		$this->_App->run();

		$this->assertTrue(
			$this->_App->DidCrash
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

	public function testGetParameters()
	{
		$this->_App->run( [ 'test' => 'test' ] );
		$this->assertTrue(
			is_array( $this->_App->getParameters() )
		);
	}

	public function testLogging()
	{
		$this->_App->run();
		$this->assertTrue( file_exists( 'examples/test.log') );
	}

	public function testTimeZone()
	{
		$this->_App->run();
		$this->assertEquals( 'US/Central', date_default_timezone_get() );
	}

	/**
	 * @throws Exception
	 */
	public function testInitializers()
	{
		$this->_App->setRegistryObject( 'Initializers.Path', 'examples/Initializers' );
		$this->_App->setRegistryObject( 'Initializers.Namespace', 'ComponentTest\Initializers\\' );
		$this->_App->run();

		$this->assertEquals(
			'Hello World!',
			Registry::getInstance()->get( 'examples\Initializers\InitTest' )
		);
	}

	public function testEventListeners()
	{
		$State = TestListener::$Count;
		$this->_App->run();

		$this->assertEquals(
			$State + 1,
			TestListener::$Count
		);
	}

	public function testNullSource()
	{
		$State = TestListener::$Count;
		$App = new AppMock( "1.0" );

		$this->_App->run();

		$this->assertEquals(
			$State + 1,
			TestListener::$Count
		);
	}
}

