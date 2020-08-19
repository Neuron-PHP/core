<?php

namespace Neuron\Log;

/**
 * Class Data
 * @package Neuron\Log
 */

class Data
{
	public $TimeStamp;
	public $Text;
	public $Level;
	public $LevelText;

	/**
	 * @param $TimeStamp
	 * @param $sText
	 * @param $iLevel
	 * @param $sLevel
	 */

	public function __construct( int $TimeStamp, string $sText, int $iLevel, string $sLevel )
	{
		$this->TimeStamp = $TimeStamp;
		$this->Text      = $sText;
		$this->Level     = $iLevel;
		$this->LevelText = $sLevel;
	}
}
