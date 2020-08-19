<?php

namespace Neuron\Data\Filter;

class Get implements IFilter
{
	public function filterScalar( $Data )
	{
		return filter_input( INPUT_GET, $Data );
	}

	public function filterArray( $Data )
	{
		return filter_input(INPUT_GET, $Data,FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
	}
}
