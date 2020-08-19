<?php

use Neuron\Log\Destination\WebHookPost;
use PHPUnit\Framework\TestCase;

class WebHookPostTest extends TestCase
{
	public function testOpenFail()
	{
		$Post = new WebHookPost( new \Neuron\Log\Format\CSV() );

		$Fail = false;
		try
		{
			$Post->open(
				[
					'endpoint' => 'fail'
				]
			);
		}
		catch( Exception $Exception )
		{
			$Fail = true;
		}

		$this->assertTrue( $Fail );
	}

	public function testOpenPass()
	{
		$Post = new WebHookPost( new \Neuron\Log\Format\CSV() );

		$Fail = false;
		try
		{
			$Post->open(
				[
					'endpoint' => 'http://www.example.org'
				]
			);
		}
		catch( Exception $Exception )
		{
			$Fail = true;
		}

		$this->assertFalse( $Fail );
	}

}
