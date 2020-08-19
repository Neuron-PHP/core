<?php

namespace Neuron\Log\Destination;

use Neuron\Data\Validation\Url;
use Neuron\Log;
use Neuron\Util\WebHook;

class Slack extends DestinationBase
{
	private $_Webhook;
	private $_Params;

	/**
	 * 'channel'     => $channel,
	 * 'username'    => $bot_name,
	 * 'text'        => $message,
	 * 'icon_emoji'  => $icon,
	 * 'attachments' => $attachments
	 *
	 * @param array $Params
	 * @return bool
	 *
	 * @throws
	 */
	public function open( array $Params ) : bool
	{
		$Validator = new Url();

		if( !$Validator->isValid( $Params[ 'endpoint' ] ) )
		{
			throw new \Exception( $Params[ 'endpoint' ].' is not a valid url.' );
		}

		$this->_Webhook = $Params[ 'endpoint' ];
		$this->_Params  = $Params[ 'params' ];

		return true;
	}

	public function close()
	{
	}

	/**
	 * @param $Text
	 * @param Log\Data $Data
	 * @return void
	 *
	 * @SuppressWarnings(PHPMD)
	 */

	public function write( string $Text, Log\Data $Data )
	{
		$this->_Params[ 'text' ] = $Text;
		$DataString = json_encode( $this->_Params );

		$WebHook = (new WebHook() )
			->postJson( $this->_Webhook, $DataString );
	}
}
