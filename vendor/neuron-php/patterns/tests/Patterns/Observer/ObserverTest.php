<?php

namespace Patterns\Observer;

use Neuron\Patterns\Observer\IObserver;
use Neuron\Patterns\Observer\ObservableTrait;
use Observable;
use PHPUnit\Framework\TestCase;

class ObserveMe
{
	use ObservableTrait;

	public function doIt()
	{
		$this->notifyObservers( 1 );
	}
}

class Observer implements IObserver
{
	public $State = 0;

	public function observableUpdate( $Observable, ...$param )
	{
		$this->State = $param[ 0 ];
	}
}

class Test extends TestCase
{
	/**
	 * If notification works correctly then
	 * the observer will set its state variable to that
	 * passed by the observable.
	 */
	public function testObserver()
	{
		$Observable = new ObserveMe();
		$Observer   = new Observer();

		$Observable->addObserver( $Observer );

		$Observable->doIt();;

		$this->assertEquals(
			1,
			$Observer->State
		);
	}

	/**
	 * Removing the observer after adding it will
	 * prevent its state from being updated.
	 */
	public function testRemoveObserver()
	{
		$Observable = new ObserveMe();
		$Observer   = new Observer();

		$Observable->addObserver( $Observer );
		$Observable->removeObserver( $Observer );

		$Observable->doIt();;

		$this->assertEquals(
			0,
			$Observer->State
		);
	}
}

