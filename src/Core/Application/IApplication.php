<?php

namespace Neuron\Core\Application;

use Neuron\Patterns;

/**
 * Interface IApplication
 */
interface IApplication extends Patterns\IRunnable
{
	/**
	 * @param string $Name
	 * @param string $Section
	 * @return mixed
	 */
	public function getSetting( string $Name, string $Section = 'default' );

	/**
	 * @param string $Name
	 * @param string $Value
	 * @param string $Section
	 * @return mixed
	 */
	public function setSetting( string $Name, string $Value, string $Section = 'default' );

	/**
	 * @param string $name
	 * @param mixed $object
	 * @return mixed
	 */
	public function setRegistryObject( string $name, mixed $object );

	/**
	 * @param string $name
	 * @return mixed
	 */
	public function getRegistryObject( string $name ) : mixed;
}
