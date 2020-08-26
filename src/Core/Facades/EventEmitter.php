<?php
/**
 *
 */
namespace Neuron\Core\Facades;

use Neuron\Events\Emitter;
use Neuron\Events\Broadcasters\Generic;

/**
 * Class EventEmitter
 * @package Neuron\Core\Facades
 */
class EventEmitter
{
	private Emitter $_Emitter;

	public function __construct( )
	{
		$this->_Emitter = new Emitter();

		$this->_Emitter->registerBroadcaster( new Generic() );
	}

	/**
	 * @param array $Registry
	 */
	public function registerListeners( array $Registry )
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
	public function emit( $Event )
	{
		$this->_Emitter->emit( $Event );
	}
}
