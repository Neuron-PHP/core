<?php
namespace Tests;

use Exception;
use Neuron\Core\Application\Base;
use Neuron\Core\CrossCutting\Event;

class AppMock extends Base
{
	public bool $Crash    = false;
	public bool $DidCrash = false;
	public bool $Error    = false;
	public bool $DidError = true;
	public bool $FailStart = false;

	protected function onRun() : void
	{
		if( $this->Error )
		{
			$Test = $Bogus[ 'test' ];
		}

		if( $this->Crash )
		{
			throw new Exception( 'Mock failure.' );
		}

		Event::emit( new TestEvent() );
	}

	protected function onStart() : bool
	{
		if( $this->FailStart )
		{
			return false;
		}

		return parent::onStart();
	}

	protected function onCrash( array $Error ): void
	{
		$this->DidCrash = true;

		parent::onCrash( $Error );
	}

	public function crash(): void
	{
		$this->fatalHandler();
	}

	protected function onError( string $Message ) : bool
	{
		$this->DidError = true;

		parent::onError( $Message );

		return false;
	}
}
