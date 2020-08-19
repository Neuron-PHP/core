<?php

namespace Neuron\Log\Destination;

use Neuron\Log;

/**
 * Writes log data to a file.
 * Use the 'file_name' parameter in the open param array.
 */

class File extends DestinationBase
{
	private $_sName;
	private $_hFile;

	/**
	 * @param array $Params
	 * @return bool
	 */

	public function open( array $Params ) : bool
	{
		$this->_sName = $Params[ 'file_name' ];

		$this->_hFile = @fopen( $this->_sName, 'a' );

		if( !$this->_hFile )
		{
			return false;
		}

		return true;
	}

	public function close()
	{
		if( $this->_hFile )
		{
			fclose( $this->_hFile );
		}
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
		fwrite(	$this->_hFile,
					"$text\r\n" );
	}
}
