<?php

namespace Neuron\Data\Filter;

interface IFilter
{
	public function filterScalar( $Data );
	public function filterArray( $Data );
}
