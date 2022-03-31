<?php

namespace CrossCutting;

use Core\Facades\ListenerTest;
use Core\Facades\TempEvent;
use Neuron\Core\CrossCutting\Event;
use Neuron\Events\Broadcasters\Generic;
use PHPUnit\Framework\TestCase;

class EventTest extends TestCase
{
	public function testEmit()
	{
		Event::registerBroadcaster( new Generic() );

		Event::registerListeners(
			[
				TempEvent::class => [
					ListenerTest::class
				]
			]
		);

		$Event = new TempEvent();

		Event::emit( $Event );

		$this->assertEquals(
			1,
			$Event->State
		);

	}
}
