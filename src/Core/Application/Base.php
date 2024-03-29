<?php

namespace Neuron\Core\Application;

use Neuron\Core\CrossCutting\Event;
use Neuron\Events\Broadcasters\Generic;
use Neuron\Log;
use Neuron\Util;
use Neuron\Patterns\Registry;
use Neuron\Data\Setting\Source\ISettingSource;
use Neuron\Data\Setting\Settingmanager;

/**
 * Base functionality for applications.
 */

abstract class Base implements IApplication
{
	private   ?Registry      $_Registry;
	protected array          $_Parameters;
	protected Settingmanager $_Settings;
	protected string         $_Version;

	protected bool $_HandleErrors = false;
	protected bool $_HandleFatal  = false;

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
	 * @param $Name
	 * @param string $Section
	 * @return mixed
	 */

	public function getSetting( string $Name, string $Section = 'default' )
	{
		return $this->_Settings->get( $Section, $Name );
	}

	/**
	 * @param string $Name
	 * @param string $Value
	 * @param string $Section
	 */

	public function setSetting( string $Name, string $Value, string $Section = 'default' )
	{
		$this->_Settings->set( $Section, $Name, $Value );
	}

	/**
	 * @return bool
	 */

	public function isCommandLine()
	{
		return Util\System::isCommandLine();
	}

	/**
	 * Creates and configures the default logger.
	 * @param string $Version
	 */

	public function __construct( string $Version )
	{
		$this->_Registry = Registry::getInstance();

		$this->_Version = $Version;
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
	 * @param \Exception $exception
	 * @return bool
	 * Called for any unhandled exceptions.
	 * Returning false skips executing onFinish.
	 */

	protected function onError( string $Message ) : bool
	{
		$this->log( $Message, Log\ILogger::ERROR );

		return true;
	}

	/**
	 * @return void
	 */
	protected function onCrash( array $Error ) : void
	{
		Log\Log::fatal( $Error[ 'message' ] );
	}

	/**
	 * @return void
	 */
	public function fatalHandler()
	{
		$Error = error_get_last();

		if( $Error && $Error[ 'type' ] == E_ERROR )
		{
			$this->log( $Error[ 'message' ], Log\ILogger::FATAL );

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

		Log\Log::error( sprintf( "PHP %s:  %s in %s on line %d", $Type, $Message, $File, $Line ) );

		return true;
	}

	/**
	 * @return mixed
	 * Must be implemented by derived classes.
	 */

	protected abstract function onRun();

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
	public function init(): void
	{
		$this->initErrorHandlers();
		$this->initEvents();
	}

	/**
	 * @return void
	 */
	public function initEvents(): void
	{
		Event::registerBroadcaster( new Generic() );
	}

	/**
	 * @param array $Argv
	 * @return bool
	 */
	public function run( array $Argv = [] )
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
			$this->onRun();
		}
		catch( \Exception $exception )
		{
			$Message = get_class( $exception ).', msg: '.$exception->getMessage();

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

	public function getParameters()
	{
		return $this->_Parameters;
	}

	/**
	 * @param string $param
	 * @return mixed
	 */

	public function getParameter( string $param )
	{
		return $this->_Parameters[ $param ];
	}

	/**
	 * @param $name
	 * @param $object
	 */

	public function setRegistryObject( string $name, mixed $object )
	{
		$this->_Registry->set( $name, $object );
	}

	/**
	 * @param $name
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
			set_error_handler( [
										 $this,
										 'phpErrorHandler'
									 ] );
		}

		if( $this->isHandleFatal() )
		{
			register_shutdown_function( [
													 $this,
													 'fatalHandler'
												 ] );
		}
	}
}
