<?php

namespace Neuron\Patterns\Singleton;

interface ISingleton
{
	function serialize();
	static function invalidate();
	static function instance();
}
