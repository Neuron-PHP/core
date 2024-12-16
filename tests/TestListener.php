<?php
namespace Tests;

class TestListener implements \Neuron\Events\IListener
{
	static int $Count = 0;

	public function event( $Event ): void
	{
		TestListener::$Count++;
	}
}
