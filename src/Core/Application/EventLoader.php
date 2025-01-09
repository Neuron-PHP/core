<?php

namespace Neuron\Core\Application;

use Neuron\Core\CrossCutting\Event;
use Neuron\Events\Broadcasters\Generic;
use Neuron\Log;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class EventLoader
{
	private Base $_Base;

	public function __construct( Base $base )
	{
		$this->_Base = $base;
	}

	/**
	 * @return void
	 */
	public function initEvents(): void
	{
		Event::registerBroadcaster( new Generic() );

		$Path = $this->getPath();

		if( !file_exists( $Path . '/event-listeners.yaml' ) )
		{
			Log\Log::debug( "event-listeners.yaml not found." );
			return;
		}

		try
		{
			$Data = Yaml::parseFile( $Path . '/event-listeners.yaml' );
		}
		catch( ParseException $exception )
		{
			Log\Log::error( "Failed to load event listeners: " . $exception->getMessage() );
			return;
		}

		$this->loadEvents( $Data[ 'events' ] );
	}

	/**
	 * @return string
	 */
	protected function getPath(): string
	{
		$File = $this->_Base->getBasePath() . '/config';

		if( $this->_Base->getEventListenersPath() )
		{
			$File = $this->_Base->getEventListenersPath();
		}
		return $File;
	}

	/**
	 * @param $events
	 * @return void
	 */
	protected function loadEvents( $events ): void
	{
		foreach( $events as $Event )
		{
			foreach( $Event[ 'listeners' ] as $Listener )
			{
				Event::registerListener( $Event[ 'class' ], $Listener );
			}
		}
	}
}
