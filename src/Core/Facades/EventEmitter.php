<?php
/**
 *
 */
namespace Neuron\Core\Facades;

use Neuron\Events\Broadcasters\IBroadcaster;
use Neuron\Events\Emitter;
use Neuron\Events\Broadcasters\Generic;

/**
 * Class EventEmitter
 * @package Neuron\Core\Facades
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
	 * @param IBroadcaster $Broadcaster
	 * @return void
	 */
	public function registerBroadcaster( IBroadcaster  $Broadcaster ) : void
	{
		$this->_Emitter->registerBroadcaster( $Broadcaster );
	}

	/**
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
	 * @param $Event
	 */
	public function emit( $Event ) : void
	{
		$this->_Emitter->emit( $Event );
	}
}
