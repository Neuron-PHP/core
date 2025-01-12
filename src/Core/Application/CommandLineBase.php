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
	 * Get the description of the application for --help.
	 * @return string
	 */
	protected abstract function getDescription(): string;

	/**
	 * Returns an array of all handlers for command line parameters.
	 * @return array
	 */
	protected function getHandlers(): array
	{
		return $this->_Handlers;
	}

	/**
	 * Adds a handler for command line parameters.
	 * The switch is the parameter that causes the specified method to be called.
	 * If the Param parameter is set to true, the token immediately following the
	 * switch on the command line will be passed as the parameter to the handler.
	 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
	 *
	 * @param string $Switch the name of the switch.
	 * @param string $Description the description of the switch.
	 * @param string $Method the name of the switch handler method.
	 * @param bool|bool $Param if true, the next parameter will be passed to the handler as the value of the switch.
	 */
	protected function addHandler( string $Switch, string $Description, string $Method, bool $Param = false ): void
	{
		$this->_Handlers[ $Switch ] = [
			'description'	=> $Description,
			'method'			=> $Method,
			'param'			=> $Param
		];
	}

	/**
	 * Processes all parameters passed to the application.
	 *
	 * @return bool returns false if the execution should be halted.
	 */
	protected function processParameters(): bool
	{
		$ParamCount = count( $this->getParameters() );

		for( $c = 0; $c < $ParamCount; $c++ )
		{
			$Param = $this->getParameters()[ $c ];

			if( !$this->handleParameter( $Param, $c, $this->getParameters() ) )
			{
				return false;
			}
		}

		return true;
	}

	/**
	 * Handles a single parameter passed on the command line.
	 * @param string $Param
	 * @param int $Index
	 * @return bool returns false if the execution should be halted.
	 */
	private function handleParameter( string $Param, int &$Index ): bool
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
				if( !$this->$Method( $Value ) )
				{
					return false;
				}

				continue;
			}

			return $this->$Method();
		}

		return true;
	}

	/**
	 * Activated by the --help parameter. Shows all configured switches and their
	 * hints.
	 */
	protected function help(): bool
	{
		echo basename( $_SERVER['PHP_SELF'], '.php' )."\n";
		echo 'v'.$this->getVersion()."\n";
		echo $this->getDescription()."\n\n";
		echo "Switches:\n";
		$Handlers = $this->getHandlers();
		ksort( $Handlers );

		echo str_pad( 'Switch', 15 )."Value\n";
		echo str_pad( '------', 15 )."-----\n";

		foreach( $Handlers as $Switch => $Info )
		{
			if( $Info[ 'param' ] )
			{
				$Value = str_pad( 'true', 5 );
			}
			else
			{
				$Value = str_pad( ' ', 5 );
			}

			echo str_pad( $Switch, 15 ).$Value."$Info[description]\n";
		}

		return false;
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

		if( !$this->processParameters() )
		{
			return false;
		}

		return parent::onStart();
	}
}
