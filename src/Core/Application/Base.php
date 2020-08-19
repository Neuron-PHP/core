<?php

namespace Neuron\Core\Application;

use Neuron\Setting;
use Neuron\Log;
use Neuron\Util;
use Neuron\Patterns\Registry;

/**
 * Defines base functionality for applications.
 */

abstract class Base extends Log\Base implements IApplication
{
	private		$_Logger;
	private		$_Registry;
	protected	$_aParameters;
	protected	$_Settings;

	/**
	 * @param Setting\Source\ISettingSource $Source
	 * @return $this
	 */

	public function setSettingSource( Setting\Source\ISettingSource $Source )
	{
		$this->_Settings = new Setting\SettingManager( $Source );
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
	 * @param $sName
	 * @param $sValue
	 * @param string $sSection
	 */

	public function setSetting( $sName, $sValue, $sSection = 'default' )
	{
		$this->_Settings->set( $sSection, $sName, $sValue );
	}

	/**
	 * @return bool
	 */

	public function isCommandLine()
	{
		return Util\System::isCommandLine();
	}

	/**
	 *  Creates and configures the default logger.
	 */

	public function __construct()
	{
		$this->_Registry = Registry::getInstance();

		$Destination = new Log\Destination\StdOut( new Log\Format\PlainText );
		$Log         = new Log\Logger( $Destination );

		$this->_Logger = new Log\LogMux();
		$this->_Logger->addLog( $Log );

		$this->_Logger->setRunLevel( Log\ILogger::INFO );

		parent::__construct( $this->_Logger );
	}

	/**
	 * @return bool
	 *
	 * Called before onRun. If false is returned, application terminates
	 * without executing onRun.
	 */

	protected function onStart()
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
	 */

	protected function onError( \Exception $exception )
	{
		$this->log( get_class( $exception ).', msg: '.$exception->getMessage(), Log\ILogger::ERROR );

		// Returning false skips executing onFinish.

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
	 * Must be implemented by derived classes.
	 */

	public abstract function getVersion();

	/**
	 * @param array|null $aArgv
	 * @return bool
	 */

	public function run( array $aArgv = null )
	{
		$this->_aParameters = $aArgv;

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

	//region Parameters
	/**
	 * @return array
	 *
	 * returns parameters passed to the run method.
	 */

	public function getParameters()
	{
		return $this->_aParameters;
	}

	/**
	 * @param $param
	 * @return mixed
	 */

	public function getParameter( $param )
	{
		return $this->_aParameters[ $param ];
	}
	//endregion

	//region Logging
	/**
	 * @return Log\LogMux
	 */

	public function getLogger() : Log\ILogger
	{
		return $this->_Logger;
	}

	//endregion

	//region Registry

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
	//endregion
}
