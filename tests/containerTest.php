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
	
	public function testMethodSkippedMatchingFlagName() {
		$this->container->add('Example2');
		$this->assertTrue(count($this->container->components['Example2']) == 0);
	}
	
	public function testDependencySetterUsage() {
		$this->container->add('Example3');
		$this->container->add('Example4');
		$e = $this->container->getInstance('Example4');
		
		$this->assertTrue(count($this->container->components['Example4']) == 1);
		$this->assertTrue($this->container->components['Example4'][0] == 'Example3');
	}
	
	public function testConstructorInjection() {
		$this->container->add('ConstructorExample');
		$this->container->add('Example');
		$e = $this->container->getInstance('ConstructorExample');
		
		$this->assertTrue(get_class($e->example) == 'Example');
	}
	
	public function testConstructorAndSetterInjection() {
		$this->container->add('ConstructorExample2');
		$this->container->add('Example');
		$this->container->add('Example2');
		$e = $this->container->getInstance('ConstructorExample2');
		
		$this->assertTrue(get_class($e->example) == 'Example');
		$this->assertTrue(get_class($e->example2) == 'Example2');
	}
	
	/**
	 * @expectedException CircularDependencyException 
	 */
	public function testCircularDependencyException() {
		$this->container->add('Circular1');
		$this->container->add('Circular2');
		$e = $this->container->getInstance('Circular1');
	}
}

?>
