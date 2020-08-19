<?php

namespace Neuron\Patterns\Observer;

/**
 * Interface IObserver
 * @package Neuron\Patterns\Observer
 */

interface IObserver
{
	/**
	 * @param $Observable
	 * @param $param
	 * @return mixed
	 */

	function observableUpdate( $Observable, ...$param );
}
