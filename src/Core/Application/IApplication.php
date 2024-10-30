<?php

namespace Neuron\Core\Application;

use Neuron\Patterns;
use Neuron\Log;

/**
 * Provides the runnable interface plus access to basic cross-cutting concerns:
 * - Logging
 * - Settings
 * - Registry
 */
interface IApplication extends Patterns\IRunnable
{
	public function getSetting( string $Name, string $Section = 'default' );
	public function setSetting( string $Name, string $Value, string $Section = 'default' );

	public function setRegistryObject( string $name, mixed $object );
	public function getRegistryObject( string $name ) : mixed;

	public function init() : void;
	public function initEvents() : void;
}
