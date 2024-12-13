<?php

namespace ComponentTest\Initializers;

use Neuron\Patterns\IRunnable;
use Neuron\Patterns\Registry;

class InitTest implements IRunnable
{
	public function run( array $Argv = [] ): mixed
	{
		Registry::getInstance()
				  ->set( 'examples\Initializers\InitTest', 'Hello World!' );

		return true;
	}
}
