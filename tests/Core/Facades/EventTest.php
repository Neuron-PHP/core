<?php

namespace Core\Facades;

use Neuron\Core\Facades\EventEmitter;
use Neuron\Events\Broadcasters\Generic;
use Neuron\Events\Emitter;
use Neuron\Events\IEvent;
use Neuron\Events\IListener;
use PHPUnit\Framework\TestCase;

class TempEvent implements IEvent
{
	public int $State = 0;
}

class ListenerTest implements IListener
{
	public function event( $Event )
	{
		$Event->State = 1;
	}
}

class EventTest extends TestCase
{
	public Emitter $Emitter;

	public function setUp() : void
	{
		parent::setUp();

		$this->Emitter = new Emitter();
	}

	public function testEmit()
	{
		$Emitter = new EventEmitter();

		$Emitter->registerBroadcaster( new Generic() );

		$Emitter->registerListeners(
			[
				TempEvent::class => [
					ListenerTest::class
				]
			]
		);

		$Event = new TempEvent();
		$Emitter->emit( $Event );

		$this->assertEquals(
			1,
			$Event->State
		);
	}
}
