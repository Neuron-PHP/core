<?php
require_once( '../vendor/autoload.php' );

use Neuron\Log;

class cli extends Neuron\Application\CommandLineBase
{
	public function getVersion()
	{
		return '0.1';
	}

	protected function date()
	{
		echo date( 'Y-m-d' )." ";
	}

	protected  function time()
	{
		echo date( 'H:m:j' )." ";
	}

	protected function say( $text )
	{
		echo $text."\n";
	}

	protected function onStart()
	{
		$this->addHandler(
			'-d',
			'Shows the current date',
			'date' );

		$this->addHandler(
			'-t',
			'Shows the current time',
			'time' );

		// The true parameter tells the parser to pass the next command line argument
		// as a parameter to the handler method.

		$this->addHandler(
			'-s',
			'Echo the next parameter',
			'say',
			true );

		return parent::onStart();
	}

	public function onRun()
	{
	}

	public function __construct()
	{
		parent::__construct();

		$Destination	= new Log\Destination\Null( new Log\Format\PlainText );
		$this->getLogger()->setDestination( $Destination );

	}
}

date_default_timezone_set( 'UTC' );


$app = new cli();

$app->run( $argv );
