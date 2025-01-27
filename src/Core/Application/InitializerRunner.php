<?php

namespace Neuron\Core\Application;

/**
 * Loads and executes all initializers in the app/Initializers directory.
 */
class InitializerRunner
{
	private Base $base;

	public function __construct( Base $base )
	{
		$this->base = $base;
	}

	/**
	 * @return void
	 */

	public function execute(): void
	{
		$initializersPath = $this->getPath();

		$namespace = $this->getNamespace();

		foreach( glob( $initializersPath . '/*.php' ) as $filename )
		{
			require_once $filename;

			$className = basename( $filename, '.php' );

			$fullyQualifiedClassName = $namespace . $className;

			$this->runInitializer( $fullyQualifiedClassName );
		}
	}

	/**
	 * @return mixed|string
	 */

	protected function getPath(): mixed
	{
		$initializersPath = $this->base->getBasePath() . '/app/Initializers';

		if( $this->base->getRegistryObject( 'Initializers.Path' ) )
		{
			$initializersPath = $this->base->getRegistryObject( 'Initializers.Path' );
		}
		return $initializersPath;
	}

	/**
	 * @return mixed|string
	 */

	protected function getNamespace(): mixed
	{
		$namespace = 'App\\Initializers\\';

		if( $this->base->getRegistryObject( 'Initializers.Namespace' ) )
		{
			$namespace = $this->base->getRegistryObject( 'Initializers.Namespace' );
		}
		return $namespace;
	}

	/**
	 * @param string $fullyQualifiedClassName
	 * @return void
	 */

	protected function runInitializer( string $fullyQualifiedClassName ): void
	{
		if( class_exists( $fullyQualifiedClassName ) )
		{
			$initializer = new $fullyQualifiedClassName;

			if( method_exists( $initializer, 'run' ) )
			{
				$initializer->run();
			}
		}
	}
}
