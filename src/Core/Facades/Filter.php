<?php
/**
 * Wrapper for the filter classes.
 */

namespace Neuron\Core\Facades;

use Neuron\Data\Filter\Cookie;
use Neuron\Data\Filter\Get;
use Neuron\Data\Filter\Post;
use Neuron\Data\Filter\Server;
use Neuron\Data\Filter\Session;

/**
 * Class Filter
 * @package Neuron\Core\Facades
 */
class Filter
{
	private Get     $_Get;
	private Post    $_Post;
	private Server  $_Server;
	private Session $_Session;
	private Cookie  $_Cookie;

	public function __construct()
	{
		$this->_Get     = new Get;
		$this->_Post    = new Post;
		$this->_Server  = new Server;
		$this->_Session = new Session;
		$this->_Cookie  = new Cookie;
	}

	/**
	 * @return Get
	 */
	public function get(): Get
	{
		return $this->_Get;
	}

	/**
	 * @return Post
	 */
	public function post(): Post
	{
		return $this->_Post;
	}

	/**
	 * @return Server
	 */
	public function server(): Server
	{
		return $this->_Server;
	}

	/**
	 * @return Session
	 */
	public function session(): Session
	{
		return $this->_Session;
	}

	/**
	 * @return Cookie
	 */
	public function cookie(): Cookie
	{
		return $this->_Cookie;
	}
}
