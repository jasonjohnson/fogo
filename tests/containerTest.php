<?php

include '../container.php';

// Our local test entities.
include 'entities.php';

class ContainerTest extends PHPUnit_Framework_TestCase
{
	var $container;
	
	public function setUp() {
		$this->container = new Container();
	}
	
	public function testObjectsEqual() {
		$this->container->add('Example');
		$e1 = $this->container->getInstance('Example');
		$e2 = $this->container->getInstance('Example');
		
		$this->assertTrue($e1 === $e2);
	}
	
	public function testObjectDoesNotEqual() {
		$this->container->add('Example');
		$e1 = $this->container->getInstance('Example');
		$e2 = new Example();
		
		$this->assertTrue($e1 !== $e2);
	}
	
	public function testConstructorInjection() {
		$this->container->add('ConstructorExample');
		$this->container->add('Example');
		$e = $this->container->getInstance('ConstructorExample');
		
		$this->assertTrue(get_class($e->example) == 'Example');
	}
	
	/**
	 * @expectedException CircularDependencyException 
	 */
	public function testCircularDependencyException() {
		$this->container->add('Circular1');
		$this->container->add('Circular2');
		$e = $this->container->getInstance('Circular1');
	}
	
	/**
	 * @expectedException ClassResolutionException 
	 */
	public function testUnresolvableDependencyException() {
		$this->container->add('UnresolvableDependency');
		$e = $this->container->getInstance('UnresolvableDependency');
	}
}

?>
