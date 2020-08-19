<?php
namespace Neuron\Patterns;

/**
 * Interface IRunnable
 * @package Neuron\Patterns
 */

interface IRunnable
{
	/**
	 * Generic run method.
	 * @param array|null $Argv
	 * @return mixed
	 */
	public function run( array $Argv = null );
}
