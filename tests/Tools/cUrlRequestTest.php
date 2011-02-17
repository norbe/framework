<?php
/**
 * This file is part of the Nella Framework.
 *
 * Copyright (c) 2006, 2011 Patrik Votoček (http://patrik.votocek.cz)
 *
 * This source file is subject to the GNU Lesser General Public License. For more information please see http://nellacms.com
 */
 
namespace NellaTests\Tools;

use Nella\Framework;

require_once __DIR__ . "/../bootstrap.php";

/**
 * Test: Nella cUrl wrapper
 *
 * @author	Patrik Votoček
 */
class cUrlTest extends \PHPUnit_Framework_TestCase
{
	/** @var Nella\Tools\cUrlRequest */
	private $wrapper;
	
	public function setUp()
	{
		$this->wrapper = new \Nella\Tools\cUrlRequest;
	}
	
	public function testInstace()
	{
		$this->assertInstanceOf('Nella\Tools\cUrlRequest', $this->wrapper, "is Nella\\Tools\\cUrl instance");
		$wrapper = new \Nella\Tools\cUrlRequest("http://example.com");
		$this->assertTrue(is_string($wrapper->getUrl()), "->getUrl()");
		$this->assertTrue(is_string($wrapper->url), "->url");
	}
	
	public function testOptions()
	{
		// Option
		$this->assertTrue(is_string($this->wrapper->getOption('useragent')), "->getOption()");
		$this->wrapper->setOption('useragent', "Test");
		$this->assertEquals("Test", $this->wrapper->getOption('useragent'), "->setOption()");
		
		// Options
		$this->assertTrue(is_array($this->wrapper->getOptions()), "->getOptions()");
		$this->assertTrue(is_array($this->wrapper->options), "->options");
	}
	
	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testOptionException1()
	{
		$this->wrapper->getOption('foo');
	}
	
	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testOptionException2()
	{
		$this->wrapper->setOption('foo', NULL);
	}
	
	public function testHeaders()
	{
		// Header
		$this->wrapper->setHeader('foo', "Test");
		$this->assertEquals("Test", $this->wrapper->getHeader('foo'), "->set/getHeader()");
		
		// Headers
		$this->assertTrue(is_array($this->wrapper->getHeaders()), "->getHeaders()");
		$this->assertTrue(is_array($this->wrapper->headers), "->headers");
	}
	
	public function testProxies()
	{
		$ip = "127.0.0.1";
		
		$this->wrapper->addProxy($ip);
		$this->assertTrue(is_array($this->wrapper->getProxies()), "->getProxies()");
		$this->assertTrue(is_array($this->wrapper->proxies), "->proxies");
		$proxy = $this->wrapper->proxies[0];
		$this->assertEquals($ip, $proxy->ip, "->proxies[0] + ->addProxy");
	}
	
	public function testUserAgent()
	{
		$this->assertEquals('Nella\Tools\cUrl ' . Framework::VERSION . " (http://nellacms.com)", $this->wrapper->userAgent, "->getUserAgent()");
		$this->assertEquals('Nella\Tools\cUrl ' . Framework::VERSION . " (http://nellacms.com)", $this->wrapper->userAgent, "->userAgent");
		$this->assertEquals("Test 1", $this->wrapper->setUserAgent("Test 1")->userAgent, "->setUserAgent()");
		$this->wrapper->userAgent = "Test 2";
		$this->assertEquals("Test 2", $this->wrapper->userAgent, "->userAgent setter");
	}
	
	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testUserAgentException()
	{
		$this->wrapper->userAgent = NULL;
	}
	
	public function testInfo()
	{
		$this->assertTrue(is_array($this->wrapper->getInfo()), "->getInfo()");
		$this->assertTrue(is_array($this->wrapper->info), "->info");
	}
	
	public function testGetResponse()
	{
		$this->assertInstanceOf('Nella\Tools\cUrlResponse', $this->wrapper->getResponse(\Nella\Tools\cUrlRequest::HEAD, "http://www.google.com"));
	}
}
