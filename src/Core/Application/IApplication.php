<?php
/**
 * The goal of the IApplication interface is to provide access to basic application services:
 * - Logging
 * - Settings
 * - Registry
 */

namespace Neuron\Core\Application;

use Neuron\Patterns;
use Neuron\Log;

/**
 * Interface IApplication
 * @package Neuron\Core\Application
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
