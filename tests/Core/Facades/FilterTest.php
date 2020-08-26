<?php

namespace Facades;

use Neuron\Core\Facades\Filters;
use Neuron\Data\Filter\Cookie;
use Neuron\Data\Filter\Get;
use Neuron\Data\Filter\Post;
use Neuron\Data\Filter\Server;
use Neuron\Data\Filter\Session;
use PHPUnit\Framework\TestCase;

class FilterTest extends TestCase
{
	public Filters $Filter;

	protected function setUp()
	{
		parent::setUp();

		$this->Filter = new Filters();
	}

	public function testGet()
	{
		$this->assertTrue(
			$this->Filter->get() instanceof Get
		);
	}

	public function testPost()
	{
		$this->assertTrue(
			$this->Filter->post() instanceof Post
		);
	}

	public function testServer()
	{
		$this->assertTrue(
			$this->Filter->server() instanceof Server
		);
	}

	public function testCookie()
	{
		$this->assertTrue(
			$this->Filter->cookie() instanceof Cookie
		);
	}

	public function testSession()
	{
		$this->assertTrue(
			$this->Filter->session() instanceof Session
		);
	}
}
