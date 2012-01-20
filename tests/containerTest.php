<?php

include '../container.php';

class Example {
}

class Example2 {
	function set() {}
}

class Example3 {
}

class Example4 {
	var $example3;
	function setExample3($example3) {
		$this->example3 = $example3;
	}
}

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
		// Example4 depends on Example3.
		$this->container->add('Example3');
		$this->container->add('Example4');
		$e = $this->container->getInstance('Example4');
		
		$this->assertTrue(count($this->container->components['Example4']) == 1);
		$this->assertTrue($this->container->components['Example4'][0] == 'Example3');
	}
}

?>
