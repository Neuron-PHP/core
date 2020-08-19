<?php
namespace Neuron\Patterns\Observer;

use Neuron\Data\ArrayHelper;

/**
 * Class ObservableTrait
 * @package Neuron\Patterns\Observer
 */

trait ObservableTrait
{
	private $_Observers = [];

	/**
	 * Add an observer to the notification list.
	 * @param IObserver $Observer
	 */

	public function addObserver( IObserver $Observer )
	{
		$this->_Observers[] = $Observer;
	}

	/**
	 * Remove an observer from the notification list.
	 * @param IObserver $Observer
	 */
	public function removeObserver( IObserver $Observer )
	{
		ArrayHelper::remove( $this->_Observers, $Observer );
	}

	/**
	 * @param mixed $params, ...
	 */

	public function notifyObservers( ...$params )
	{
		foreach( $this->_Observers as $Observer )
		{
			$Observer->observableUpdate( $this, ...$params );
		}
	}
}
