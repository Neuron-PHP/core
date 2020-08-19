<?php
namespace Neuron\Data\Filter;

class Server implements IFilter
{
	public function filterScalar( $Data )
	{
		return filter_input( INPUT_SERVER, $Data );
	}

	public function filterArray( array $Data )
	{
		return filter_input(INPUT_SERVER, $Data,FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
	}
}
