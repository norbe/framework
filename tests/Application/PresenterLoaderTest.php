<?php
/**
 * This file is part of the Nella Framework.
 *
 * Copyright (c) 2006, 2011 Patrik Votoček (http://patrik.votocek.cz)
 *
 * This source file is subject to the GNU Lesser General Public License. For more information please see http://nellacms.com
 */

namespace NellaTests\Application;

use Nella\Application\PresenterLoader;

require_once __DIR__ . "/../bootstrap.php";

class PresenterLoaderTest extends \PHPUnit_Framework_TestCase
{
	/** @var Nella\Application\PresenterLoader */
	private $loader;
	
	public function setUp()
	{
		$this->loader = new \Nella\Application\PresenterLoader(__DIR__);
	}
	
	/**
	 * @covers Nella\Application\PresenterLoader::__construct
	 * @covers Nella\Application\PresenterLoader::createPresenterLoader
	 */
	public function testInstance()
	{
		$this->assertInstanceOf('Nella\Application\PresenterLoader', $this->loader);
		$this->assertInstanceOf('Nella\Application\PresenterLoader', \Nella\Application\PresenterLoader::createPresenterLoader());
	}

	/**
	 * @covers Nella\Application\PresenterLoader::formatPresenterClass
	 */
	public function testFormatPresenterClass()
	{
		$this->assertEquals('App\FooPresenter', $this->loader->formatPresenterClass('Foo'), "->formatPresenterClass('Foo')");
		$this->assertEquals('App\Foo\BarPresenter', $this->loader->formatPresenterClass('Foo:Bar'), "->formatPresenterClass('Foo:Bar')");
		$this->assertEquals('App\Foo\Bar\BazPresenter', $this->loader->formatPresenterClass('Foo:Bar:Baz'), "->formatPresenterClass('Foo:Bar:Baz')");
		$this->assertEquals('Nella\FooPresenter', $this->loader->formatPresenterClass('Foo', 'lib'), "->formatPresenterClass('Foo', 'lib')");
		$this->assertEquals('Nella\Foo\BarPresenter', $this->loader->formatPresenterClass('Foo:Bar', 'lib'), "->formatPresenterClass('Foo:Bar', 'lib')");
		$this->assertEquals('Nella\Foo\Bar\BazPresenter', $this->loader->formatPresenterClass('Foo:Bar:Baz', 'lib'), "->formatPresenterClass('Foo:Bar:Baz', 'lib')");
	}

	/**
	 * @covers Nella\Application\PresenterLoader::unformatPresenterClass
	 */
	public function testUnformatPresenterClass()
	{
		$this->assertEquals('Foo', $this->loader->unformatPresenterClass('App\FooPresenter'), "->unformatPresenterClass('App\\FooPresenter')");
		$this->assertEquals('Foo:Bar', $this->loader->unformatPresenterClass('App\Foo\BarPresenter'), "->unformatPresenterClass('App\\Foo\\BarPresenter')");
		$this->assertEquals('Foo:Bar:Baz', $this->loader->unformatPresenterClass('App\Foo\Bar\BazPresenter'), "->unformatPresenterClass('App\\Foo\\Bar\\BazPresenter')");
		$this->assertEquals('Foo', $this->loader->unformatPresenterClass('Nella\FooPresenter'), "->unformatPresenterClass('Nella\\FooPresenter')");
		$this->assertEquals('Foo:Bar', $this->loader->unformatPresenterClass('Nella\Foo\BarPresenter'), "->unformatPresenterClass('Nella\\Foo\\BarPresenter')");
		$this->assertEquals('Foo:Bar:Baz', $this->loader->unformatPresenterClass('Nella\Foo\Bar\BazPresenter'), "->unformatPresenterClass('Nella\\Foo\\Bar\\BazPresenter')");
	}
	
	/**
	 * @covers Nella\Application\PresenterLoader::getPresenterClass
	 */
	public function testGetPresenterClass()
	{
		$name = 'Foo';
		$this->assertEquals('App\FooPresenter', $this->loader->getPresenterClass($name), "->getPresenterClass('$name')");
		$name = 'Bar:Foo';
		$this->assertEquals('App\Bar\FooPresenter', $this->loader->getPresenterClass($name), "->getPresenterClass('$name')");
		$name = 'My';
		$this->assertEquals('Nella\MyPresenter', $this->loader->getPresenterClass($name), "->getPresenterClass('$name')");
		$name = 'Foo:My';
		$this->assertEquals('Nella\Foo\MyPresenter', $this->loader->getPresenterClass($name), "->getPresenterClass('$name')");
	}
	
	/**
	 * @covers Nella\Application\PresenterLoader::getPresenterClass
	 * @expectedException Nette\Application\InvalidPresenterException
	 */
	public function testGetPresenterClassException1()
	{
		$name = NULL;
		$this->loader->getPresenterClass($name);
	}
	
	/**
	 * @covers Nella\Application\PresenterLoader::getPresenterClass
	 * @expectedException Nette\Application\InvalidPresenterException
	 */
	public function testGetPresenterClassException2()
	{
		$name = 'Bar';
		$this->loader->getPresenterClass($name);
	}
	
	/**
	 * @covers Nella\Application\PresenterLoader::getPresenterClass
	 * @expectedException Nette\Application\InvalidPresenterException
	 */
	public function testGetPresenterClassException3()
	{
		$name = 'Baz';
		$this->loader->getPresenterClass($name);
	}
	
	/**
	 * @covers Nella\Application\PresenterLoader::getPresenterClass
	 * @expectedException Nette\Application\InvalidPresenterException
	 */
	public function testGetPresenterClassException4()
	{
		$name = 'Bar';
		$this->loader->getPresenterClass($name);
	}
	
	/**
	 * @covers Nella\Application\PresenterLoader::getPresenterClass
	 * @expectedException Nette\Application\InvalidPresenterException
	 */
	public function testGetPresenterClassException5()
	{
		$this->loader->caseSensitive = TRUE;
		
		$name = 'my';
		$this->loader->getPresenterClass($name);
	}
}