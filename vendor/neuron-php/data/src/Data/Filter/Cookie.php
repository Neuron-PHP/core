<?php

namespace Neuron\Data\Filter;

class Cookie implements IFilter
{
	public function filterScalar( $Data )
	{
		return filter_input(INPUT_COOKIE, $Data );
	}

	public function filterArray( array $Data )
	{
		return filter_input(INPUT_COOKIE, $Data,FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
	}
}
