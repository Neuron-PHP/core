<?php

namespace Neuron\Log\Destination;

use Neuron\Log;

class Email
{
	private $_To;
	private $_From;
	private $_Subject;

	/**
	 * @param array $Params
	 * @return bool
	 */

	public function open( array $Params ) : bool
	{
		$this->_To      = $Params[ 'to' ];
		$this->_From    = $Params[ 'from' ];
		$this->_Subject = $Params[ 'subject' ];

		return true;
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
		mail(
			$this->_To,
			$this->_Subject,
			$Text
		);
	}
}
