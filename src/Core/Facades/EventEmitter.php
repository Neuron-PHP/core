<?php
namespace Neuron\Core\Facades;

use Neuron\Events\Broadcasters\IBroadcaster;
use Neuron\Events\Emitter;
use Neuron\Events\Broadcasters\Generic;

/**
 * Wrapper for event functionality.
 */
class EventEmitter
{
	private Emitter $_Emitter;

	/**
	 *
	 */
	public function __construct( )
	{
		$this->_Emitter = new Emitter();
	}

	/**
	 * Registers a new broadcaster.
	 * @param IBroadcaster $Broadcaster
	 * @return void
	 */
	public function registerBroadcaster( IBroadcaster $Broadcaster ) : void
	{
		$this->_Emitter->registerBroadcaster( $Broadcaster );
	}

	/**
	 * Maps an array of events to an array of listeners.
	 * Listeners can either be an object or a class name
	 * to be instantiated when the event is fired.
	 *
	 * @param array $Registry
	 */
	public function registerListeners( array $Registry ) : void
	{
		$Broadcasters = $this->_Emitter->getBroadcasters();

		foreach( $Broadcasters as $Broadcaster )
		{
			foreach( $Registry as $Class => $Listeners )
			{
				foreach( $Listeners as $Listener )
				{
					$Broadcaster->addListener( $Class, $Listener );
				}
			}
		}
	}

	/**
	 * Emits an event across all broadcasters to all registered
	 * listeners.
	 * @param $Event
	 */
	public function emit( $Event ) : void
	{
		$this->_Emitter->emit( $Event );
	}
}
