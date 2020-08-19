<?php
namespace Neuron\Data\Filter;

class Session implements IFilter
{
	public function filterScalar( $Data )
	{
		return filter_var( $_SESSION[ $Data ] );
	}

	public function filterArray( array $Data )
	{
		return filter_var_array( $Data );
	}
}
