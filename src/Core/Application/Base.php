<?php

namespace Neuron\Core\Application;

use Neuron\Log;
use Neuron\Util;
use Neuron\Patterns\Registry;
use Neuron\Data\Setting\Source\ISettingSource;
use Neuron\Data\Setting\Settingmanager;

/**
 * Defines base functionality for applications.
 */

abstract class Base extends Log\Base implements IApplication
{
	private   Log\ILogger  $_Logger;
	private   ?Registry $_Registry;
	protected array    $_Parameters;
	protected Settingmanager $_Settings;
	protected string $_Version;

	/**
	 * @param ISettingSource $Source
	 * @return $this
	 */

	public function setSettingSource( ISettingSource $Source )
	{
		$this->_Settings = new SettingManager( $Source );
		return $this;
	}

	/**
	 * @param $sName
	 * @param string $sSection
	 * @return mixed
	 */

	public function getSetting( $sName, $sSection = 'default' )
	{
		return $this->_Settings->get( $sSection, $sName );
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

		$Destination = new Log\Destination\StdOut( new Log\Format\PlainText );
		$Log         = new Log\Logger( $Destination );

		$this->_Logger = new Log\LogMux();
		$this->_Logger->addLog( $Log );

		$this->_Logger->setRunLevel( Log\ILogger::INFO );

		$this->_Version = $Version;

		parent::__construct( $this->_Logger );
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

	protected function onError( \Exception $exception ) : bool
	{
		$this->log( get_class( $exception ).', msg: '.$exception->getMessage(), Log\ILogger::ERROR );

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
	 * @param array $Argv
	 * @return bool
	 */

	public function run( array $Argv = [] )
	{
		$this->_Parameters = $Argv;

		if( !$this->onStart() )
		{
			$this->log( "onStart() returned false. Aborting.", Log\ILogger::FATAL );
			return false;
		}

		try
		{
			$this->onRun();
		}
		catch( \Exception $exception )
		{
			if( !$this->onError( $exception ) )
			{
				return false;
			}
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
	 * @return Log\LogMux
	 */

	public function getLogger() : Log\ILogger
	{
		return $this->_Logger;
	}

	/**
	 * @param $name
	 * @param $object
	 */

	public function setRegistryObject( $name, $object )
	{
		$this->_Registry->set( $name, $object );
	}

	/**
	 * @param $name
	 * @return mixed
	 */

	public function getRegistryObject( $name )
	{
		return $this->_Registry->get( $name );
	}
}
