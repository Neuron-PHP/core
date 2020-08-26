<?php

namespace Core\Facades;

use Neuron\Core\Facades\EventEmitter;
use Neuron\Events\Emitter;
use Neuron\Events\IEvent;
use Neuron\Events\IListener;
use PHPUnit\Framework\TestCase;

class TempEvent implements IEvent
{
	public int $State = 1;
}

class ListenerTest implements IListener
{
	public int $State = 0;

	public function event( $Event )
	{
		$this->State = $Event->State;
	}
}

class EventTest extends TestCase
{
	public Emitter $Emitter;

	protected function setUp()
	{
		parent::setUp();

		$this->Emitter = new Emitter();
	}

	public function testEmit()
	{
		$Event = new EventEmitter();

		$Listener = new ListenerTest();

		$Event->registerListeners(
			[
				TempEvent::class => [
					$Listener
				]
			]
		);

		$Event->emit( new TempEvent() );

		$this->assertEquals(
			1,
			$Listener->State
		);

	}
}
