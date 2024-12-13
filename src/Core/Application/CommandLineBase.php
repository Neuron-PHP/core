<?php

namespace Neuron\Core\Application;

use Neuron\Log;

/**
 * Base functionality for command line applications.
 * Command line applications are designed to only be executed from the context
 * of the php-cli.
 * Allows for easy addition and handling of command line parameters.
 */

abstract class CommandLineBase extends Base
{
	private array $_Handlers;

	/**
	 * @return array - accessor for the parameter array.
	 */

	protected function getHandlers(): array
	{
		return $this->_Handlers;
	}

	/**
	 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
	 * @param $Switch
	 * @param $Description
	 * @param $Method
	 * @param bool|bool $Param
	 *
	 * Adds a handler for command line parameters.
	 * The switch is the parameter that causes the specified method to be called.
	 * If the bParam parameter is set to true, the token immediately following the
	 * switch on the command line will be passed as the parameter to the handler.
	 */

	protected function addHandler( $Switch, $Description, $Method, bool $Param = false ): void
	{
		$this->_Handlers[ $Switch ] = [
			'description'	=> $Description,
			'method'			=> $Method,
			'param'			=> $Param
		];
	}

	/**
	 * Processes the argv array.
	 */

	protected function processParameters(): void
	{
		$ParamCount = count( $this->getParameters() );

		for( $c = 0; $c < $ParamCount; $c++ )
		{
			$Param = $this->getParameters()[ $c ];

			$this->handleParameter( $Param, $c, $this->getParameters() );
		}
	}

	private function handleParameter( string $Param, int &$Index )
	{
		foreach( $this->getHandlers() as $Switch => $Info )
		{
			if( $Switch != $Param )
			{
				continue;
			}

			$Method = $Info[ 'method' ];

			if( $Info[ 'param' ] )
			{
				$Index++;
				$Value = $this->getParameters()[ $Index ];
				$this->$Method( $Value );
				continue;
			}

			$this->$Method();
		}
	}

	/**
	 * Activated by the --help parameter. Shows all configured switches and their
	 * hints.
	 */

	protected function help(): void
	{
		echo basename( $_SERVER['PHP_SELF'], '.php' )."\n";
		echo 'v'.$this->getVersion()."\n";
		echo "Switches:\n";
		$aHandlers = $this->getHandlers();
		ksort( $aHandlers );

		foreach( $aHandlers as $sSwitch => $aInfo )
		{
			echo str_pad( $sSwitch, 20 )."$aInfo[description]\n";
		}
	}

	/**
	 * Called by ApplicationBase. Returning false terminates the application.
	 *
	 * @return bool
	 */

	protected function onStart() : bool
	{
		if( !$this->isCommandLine() )
		{
			Log\Log::fatal( "Application must be run from the command line." );
			return false;
		}

		$this->addHandler( '--help', 'Help', 'help' );

		$this->processParameters();

		return parent::onStart();
	}
}
