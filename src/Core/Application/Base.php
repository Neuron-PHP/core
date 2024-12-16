<?php

namespace Neuron\Core\Application;

use Exception;
use Neuron\Core\CrossCutting\Event;
use Neuron\Events\Broadcasters\Generic;
use Neuron\Log;
use Neuron\Log\ILogger;
use Neuron\Log\Logger;
use Neuron\Util;
use Neuron\Patterns\Registry;
use Neuron\Data\Setting\Source\ISettingSource;
use Neuron\Data\Setting\SettingManager;
use Symfony\Component\Yaml\Yaml;

/**
 * Base functionality for applications.
 */

abstract class Base implements IApplication
{
	private		string				$_BasePath;
	private		string				$_EventListenersPath;
	private		?Registry			$_Registry;
	protected	array					$_Parameters;
	protected	?Settingmanager	$_Settings = null;
	protected	string       		$_Version;
	protected	bool 					$_HandleErrors = false;
	protected	bool					$_HandleFatal  = false;

	/**
	 * Initial setup for the application.
	 * @param string $Version
	 * @param ISettingSource|null $Source
	 */

	public function __construct( string $Version, ?ISettingSource $Source = null )
	{
		$this->_BasePath = '.';

		$this->_Registry = Registry::getInstance();

		$this->_Version = $Version;

		if( !$Source )
		{
			return;
		}

		$this->_Settings = new SettingManager( $Source );
		$this->_EventListenersPath = $this->getSetting( 'event_listeners', 'paths' );
	}

	/**
	 * @return string
	 */
	public function getEventListenersPath(): string
	{
		return $this->_EventListenersPath;
	}

	/**
	 * @param string $EventListenersPath
	 * @return Base
	 */
	public function setEventListenersPath( string $EventListenersPath ): Base
	{
		$this->_EventListenersPath = $EventListenersPath;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getBasePath(): string
	{
		return $this->_BasePath;
	}

	/**
	 * @param string $BasePath
	 * @return Base
	 */
	public function setBasePath( string $BasePath ): Base
	{
		$this->_BasePath = $BasePath;
		return $this;
	}

	/**
	 * @throws Exception
	 */

	public function initLogger(): void
	{
		/** @var Log\Log $Log */
		$Log = Log\Log::getInstance();

		$Log->initIfNeeded();

		$Log->Logger->reset();

		// Create a new default logger using the destination and format
		// specified in the settings.

		$DestClass   = $this->getSetting( "destination", "logging" );
		$FormatClass = $this->getSetting( "format", "logging" );

		if( !$DestClass || !$FormatClass )
		{
			return;
		}

		$Destination = new $DestClass( new $FormatClass() );

		$DefaultLog = new Logger( $Destination );

		$FileName = $this->getSetting( "file", "logging" );
		if( $FileName )
			$Destination->open( [ 'file_name' => $FileName ] );

		$DefaultLog->setRunLevel( $this->getSetting( "level", "logging" ) ?? (int)ILogger::DEBUG );

		$Log->Logger->addLog( $DefaultLog );

		$Log->serialize();
	}

	/**
	 * @return bool
	 */

	public function isHandleErrors(): bool
	{
		return $this->_HandleErrors;
	}

	/**
	 * @param bool $HandleErrors
	 * @return Base
	 */

	public function setHandleErrors( bool $HandleErrors ): Base
	{
		$this->_HandleErrors = $HandleErrors;
		return $this;
	}

	/**
	 * @return bool
	 */

	public function isHandleFatal(): bool
	{
		return $this->_HandleFatal;
	}

	/**
	 * @param bool $HandleFatal
	 * @return Base
	 */

	public function setHandleFatal( bool $HandleFatal ): Base
	{
		$this->_HandleFatal = $HandleFatal;
		return $this;
	}

	/**
	 * @param ISettingSource $Source
	 * @return $this
	 */

	public function setSettingSource( ISettingSource $Source ) : Base
	{
		$this->_Settings = new SettingManager( $Source );
		return $this;
	}

	/**
	 * @param string $Name
	 * @param string $Section
	 * @return mixed
	 */

	public function getSetting( string $Name, string $Section = 'default' ): mixed
	{
		return $this->_Settings?->get( $Section, $Name );
	}

	/**
	 * @param string $Name
	 * @param string $Value
	 * @param string $Section
	 */

	public function setSetting( string $Name, string $Value, string $Section = 'default' ): void
	{
		if( !$this->_Settings )
		{
			return;
		}

		$this->_Settings->set( $Section, $Name, $Value );
	}

	/**
	 * @return bool
	 */

	public function isCommandLine(): bool
	{
		return Util\System::isCommandLine();
	}

	/**
	 * @return bool
	 *
	 * Called before onRun. If false is returned, application terminates
	 * without executing onRun.
	 */

	protected function onStart() : bool
	{
		return true;
	}

	/**
	 * Called immediately after onRun.
	 */

	protected function onFinish()
	{
	}

	/**
	 * @param string $Message
	 * @return bool
	 * Called for any unhandled exceptions.
	 * Returning false skips executing onFinish.
	 */

	protected function onError( string $Message ) : bool
	{
		Log\Log::error( $Message );

		return true;
	}

	/**
	 * @param array $Error
	 * @return void
	 */

	protected function onCrash( array $Error ) : void
	{
		Log\Log::fatal( $Error[ 'message' ] );
	}

	/**
	 * @return void
	 */
	public function fatalHandler(): void
	{
		$Error = error_get_last();

		if( $Error && $Error[ 'type' ] == E_ERROR )
		{
			$this->onCrash( $Error );
		}
	}

	/**
	 * @param int $ErrorNo
	 * @param string $Message
	 * @param string $File
	 * @param int $Line
	 * @return bool
	 */
	public function phpErrorHandler( int $ErrorNo, string $Message, string $File, int $Line) : bool
	{
		switch( $ErrorNo )
		{
			case E_NOTICE:
			case E_USER_NOTICE:
				$Type = "Notice";
				break;

			case E_WARNING:
			case E_USER_WARNING:
				$Type = "Warning";
				break;

			case E_ERROR:
			case E_USER_ERROR:
				$Type = "Fatal Error";
				break;

			default:
				$Type = "Unknown Error";
				break;
		}

		$this->onError( sprintf( "PHP %s:  %s in %s on line %d", $Type, $Message, $File, $Line ));
		return true;
	}

	/**
	 * @return void
	 * Must be implemented by derived classes.
	 */

	protected abstract function onRun() : void;

	/**
	 * @return string
	 * Application version number.
	 */

	public function getVersion() : string
	{
		return $this->_Version;
	}

	/**
	 * @return void
	 */
	protected function executeInitializers(): void
	{
		$initializersPath = __DIR__ . '/Initializers';

		if( $this->getRegistryObject( 'Initializers.Path' ) )
		{
			$initializersPath = $this->getRegistryObject( 'Initializers.Path' );
		}

		$namespace = 'App\\Initializers\\';

		if( $this->getRegistryObject( 'Initializers.Namespace' ) )
		{
			$namespace = $this->getRegistryObject( 'Initializers.Namespace' );
		}

		foreach( glob($initializersPath . '/*.php') as $filename )
		{
			require_once $filename;

			$className = basename( $filename, '.php' );

			$fullyQualifiedClassName = $namespace . $className;

			if( class_exists( $fullyQualifiedClassName ) )
			{
				$initializer = new $fullyQualifiedClassName;

				if( method_exists($initializer, 'run') )
				{
					$initializer->run();
				}
			}
		}
	}

	/**
	 * @return void
	 * @throws Exception
	 */

	public function init(): void
	{
		date_default_timezone_set( $this->getSetting( 'timezone', 'system' ) );

		if( $this->_Settings )
		{
			$this->initLogger();
		}

		$this->initErrorHandlers();
		$this->initEvents();

		$this->executeInitializers();
	}

	/**
	 * @return void
	 */

	public function initEvents(): void
	{
		Event::registerBroadcaster( new Generic() );

		$cwd = getcwd();

		$File = $this->getBasePath().'/config';

		if( $this->getEventListenersPath() )
		{
			$File = $this->getEventListenersPath();
		}

		$Data = Yaml::parseFile( $File.'/event-listeners.yaml' );

		foreach( $Data[ 'events' ] as $Event )
		{
			foreach( $Event[ 'listeners' ] as $Listener )
			{
				Event::registerListener( $Event[ 'class' ], $Listener );
			}
		}
	}

	/**
	 * @param array $Argv
	 * @return bool
	 * @throws Exception
	 */

	public function run( array $Argv = [] ): bool
	{
		$this->init();

		$this->_Parameters = $Argv;

		if( !$this->onStart() )
		{
			Log\Log::fatal( "onStart() returned false. Aborting." );
			return false;
		}

		try
		{
			Log\Log::debug( "Running application v{$this->_Version}.." );
			$this->onRun();
		}
		catch( Exception $exception )
		{
			$Message = get_class( $exception ).', msg: '.$exception->getMessage();

			Log\Log::fatal( "Exception: $Message" );

			$this->onCrash(
					[
						'message' => $Message
					]
				);
		}

		$this->onFinish();
		return true;
	}

	/**
	 * @return array
	 *
	 * returns parameters passed to the run method.
	 */

	public function getParameters(): array
	{
		return $this->_Parameters;
	}

	/**
	 * @param string $param
	 * @return mixed
	 */

	public function getParameter( string $param ): mixed
	{
		return $this->_Parameters[ $param ];
	}

	/**
	 * @param string $name
	 * @param mixed $object
	 */

	public function setRegistryObject( string $name, mixed $object ): void
	{
		$this->_Registry->set( $name, $object );
	}

	/**
	 * @param string $name
	 * @return mixed
	 */

	public function getRegistryObject( string $name ) : mixed
	{
		return $this->_Registry->get( $name );
	}

	/**
	 * @return void
	 */

	protected function initErrorHandlers(): void
	{
		if( $this->isHandleErrors() )
		{
			set_error_handler(
				[
					$this,
					'phpErrorHandler'
				]
			);
		}

		if( $this->isHandleFatal() )
		{
			register_shutdown_function(
				[
					$this,
					'fatalHandler'
				]
			);
		}
	}
}
