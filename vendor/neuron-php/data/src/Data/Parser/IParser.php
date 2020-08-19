<?php

namespace Neuron\Data\Parser;

interface IParser
{
	public function parse($Text, $UserData = array() );
}
