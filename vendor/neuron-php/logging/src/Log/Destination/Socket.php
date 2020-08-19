<?php

namespace Neuron\Log\Destination;

use Neuron\Log;

class Socket extends DestinationBase
{
	private $_sAddress;

	/**
	 * @param array $Params
	 * @return bool
	 */

	public function open( array $Params ) : bool
	{
		$this->_sAddress = $Params[ 'ip_address' ];

		return true;
	}

	public function close()
	{
	}

	/**
	 * @param $sMsg
	 * @throws \Exception
	 */

	protected function error( string $sMsg )
	{
		$errorcode = socket_last_error();
		$errormsg  = socket_strerror($errorcode);

		throw new \Exception( "$sMsg: [$errorcode] $errormsg\n" );
	}

	/**
	 * @param $text
	 * @param Log\Data $Data
	 * @return void
	 *
	 * @SuppressWarnings(PHPMD)
	 */

	public function write( string $text, Log\Data $Data )
	{
		if( !($sock = socket_create(AF_INET, SOCK_STREAM, 0)))
		{
			$this->error( 'Could not create socket' );
		}

		if( !socket_connect($sock , $this->_sAddress , 80))
		{
			$this->error( 'Could not connect' );
		}

		if( !socket_send ( $sock , $text, strlen( $text ) , 0))
		{
			$this->error( 'Write failed' );
		}
	}
}
