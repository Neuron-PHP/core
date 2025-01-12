<?php

namespace Neuron\Core\Application;

use Neuron\Data\Setting\SettingManager;
use Neuron\Data\Setting\Source\ISettingSource;
use Exception;
use Neuron\Log;
use Neuron\Log\ILogger;
use Neuron\Log\Logger;
use Neuron\Patterns\Registry;
use Neuron\Util;

/**
 * Base functionality for applications.
 */

abstract class Base implements IApplication
{
	private		string         $_BasePath;
	private		string         $_EventListenersPath;
	private		?Registry			$_Registry;
	protected	array					$_Parameters;
	protected	?Settingmanager	$_Settings = null;
	protected	string				$_Version;
	protected	bool					$_HandleErrors = false;
	protected	bool					$_HandleFatal  = false;

	/**
	 * Initial setup for the application.
	 *
	 * Loads the config file.
	 * Initializes the logger.
	 *
	 * @param string $Version
	 * @param ISettingSource|null $Source
	 * @throws Exception
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

		try
		{
			$this->_Settings = new SettingManager( $Source );
		}
		catch( Exception $exception )
		{
			Log\Log::error( "Failed to load settings: ".$exception->getMessage() );
		}

		date_default_timezone_set( $this->getSetting( 'timezone', 'system' ) ?? 'UTC' );

		$BasePath = $this->getSetting( 'base_path', 'system' ) ?? '.';
		$this->setBasePath( $BasePath );

		$this->_EventListenersPath = $this->getSetting( 'listeners_path', 'events' ) ?? '';

		$this->initLogger();
		$this->initErrorHandlers();
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
	 * Initializes the logger based on the parameters set in config.yaml.
	 * 	destination
	 * 	format
	 * 	file
	 * 	level
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
		{
			$Destination->open(
				[
					'file_name' => $this->getBasePath().'/'.$FileName
				]
			);
		}

		$DefaultLog->setRunLevel( $this->getSetting( "level", "logging" ) ?? (int)ILogger::DEBUG );

		$Log->Logger->addLog( $DefaultLog );

		$Log->serialize();
	}

	/**
	 * @return bool
	 */
	public function willHandleErrors(): bool
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
	public function willHandleFatal(): bool
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
	 * Returns true if the application is running in command line mode.
	 * @return bool
	 */
	public function isCommandLine(): bool
	{
		return Util\System::isCommandLine();
	}

	/**
	 * Called before onRun.
	 *
	 * Initializes the event system and executes all initializers.
	 * If false is returned, application terminates without executing onRun.
	 * @return bool
	 */
	protected function onStart() : bool
	{
		Log\Log::debug( "onStart()" );

		if( !$this->_Settings )
		{
			return true;
		}

		$this->initEvents();
		$this->executeInitializers();
		return true;
	}

	/**
	 * Called immediately after onRun.
	 */
	protected function onFinish()
	{
		Log\Log::debug( "onFinish()" );
	}

	/**
	 * Called for any unhandled exceptions.
	 * Returning false skips executing onFinish.
	 *
	 * @param string $Message
	 * @return bool
	 */
	protected function onError( string $Message ) : bool
	{
		Log\Log::error( "onError(): $Message" );

		return true;
	}

	/**
	 * Called by the fatal handler if invoked.
	 *
	 * @param array $Error
	 * @return void
	 */
	protected function onCrash( array $Error ) : void
	{
		Log\Log::fatal( "onCrash(): ".$Error[ 'message' ] );
	}

	/**
	 * Handler for fatal errors.
	 * @return void
	 */
	public function fatalHandler(): void
	{
		Log\Log::debug( "fatalHandler()" );

		$Error = error_get_last();

		if( $Error && $Error[ 'type' ] == E_ERROR )
		{
			$this->onCrash( $Error );
		}
	}

	/**
	 * Handler for PHP errors.
	 *
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
	 * Must be implemented by derived classes.
	 * @return void
	 */
	protected abstract function onRun() : void;

	/**
	 * Application version number.
	 * @return string
	 */
	public function getVersion() : string
	{
		return $this->_Version;
	}

	/**
	 * Executes all initializer classes located in app/Initializers.
	 * @return void
	 */
	protected function executeInitializers(): void
	{
		Log\Log::debug( "executeInitializers()" );
		$Initializer = new InitializerRunner( $this );
		$Initializer->execute();
	}

	/**
	 * Loads event-listeners.yaml and maps all event listeners to their associated events.
	 * @return void
	 */
	public function initEvents(): void
	{
		Log\Log::debug( "initEvents()" );

		$EventLoader = new EventLoader( $this );
		$EventLoader->initEvents();
	}

	/**
	 * Call to run the application.
	 * @param array $Argv
	 * @return bool
	 * @throws Exception
	 */
	public function run( array $Argv = [] ): bool
	{
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
	 * returns parameters passed to the run method.
	 * @return array
	 */
	public function getParameters(): array
	{
		return $this->_Parameters;
	}

	/**
	 * Gets a parameter by name.
	 * @param string $param
	 * @return mixed
	 */
	public function getParameter( string $name ): mixed
	{
		return $this->_Parameters[ $name ];
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
	 * Sets up the php error and fatal handlers.
	 * @return void
	 */
	protected function initErrorHandlers(): void
	{
		if( $this->willHandleErrors() )
		{
			set_error_handler(
				[
					$this,
					'phpErrorHandler'
				]
			);
		}

		if( $this->willHandleFatal() )
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
