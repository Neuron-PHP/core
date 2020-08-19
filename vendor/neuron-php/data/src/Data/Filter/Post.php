<?php

namespace Neuron\Data\Filter;

class Post implements IFilter
{
	public function filterScalar( $Data )
	{
		return filter_input(INPUT_POST, $Data );
	}

	public function filterArray( $Data )
	{
		return filter_input(INPUT_POST, $Data,FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
	}
}
