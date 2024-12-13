<?php

namespace ComponentTest\Initializers;

use Neuron\Patterns\IRunnable;
use Neuron\Patterns\Registry;

class InitTest implements IRunnable
{
	public function run( array $Argv = [] ): void
	{
		Registry::getInstance()
				  ->set( 'examples\Initializers\InitTest', 'Hello World!' );
	}
}
