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

interface IApplication extends Log\ILogger, Patterns\IRunnable
{
	public function getSetting( $sName, $sSection = 'default' );
	public function setSetting( $sName, $sValue, $sSection = 'default' );

	public function setRegistryObject( $name, $object );
	public function getRegistryObject( $name );
}
