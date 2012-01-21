<?php
/**
 * "Fogo"
 * 
 * A prototype for a micro Dependency Injection (DI) Container.
 * 
 * @license MIT License
 * @copyright Copyright (c) 2012, Jason Johnson <jason@period-three.com>
 * @version 0.1
 */

class Container {
	public $prefix;
	public $prefixLength;
	public $instances = array();
	public $components = array();
	
	public function __construct($options = array()) {
		$this->prefix = isset($options['prefix'])?$options['prefix']:'set';
		$this->prefixLength = strlen($this->prefix);
	}
	
	public function add($name) {
		$dependencies = array();
		$constructor = null;
		
		try {
			$constructor = new ReflectionMethod($name, '__construct');
		} catch(ReflectionException $e) {
			// This is fine, simply means we will not be injecting anything.
		}
		
		if($constructor) {
			foreach($constructor->getParameters() as $parameter) {
				try {
					$dependencies[] = $parameter->getClass()->name;
				} catch(ReflectionException $e) {
					throw new ClassResolutionException();
				}
			}
		}
		
		$this->components[$name] = $dependencies;
	}
	
	public function getInstance($name) {
		if(!isset($this->instances[$name]))
			$this->resolve($name);
		return $this->instances[$name];
	}
	
	private function resolve($name) {
		$args = array();
		$dependencies = $this->components[$name];
		
		foreach($dependencies as $dependency) {
			if(in_array($name, $this->components[$dependency]))
				throw new CircularDependencyException("Circular dependency: {$name} <> {$dependency}");
			$args[] =  $this->getInstance($dependency);
		}
		
		$class = new ReflectionClass($name);
		$instance = $class->newInstanceArgs($args);
		
		$this->instances[$name] = $instance;
	}
}

class CircularDependencyException extends Exception { /* ... */ }
class ClassResolutionException extends Exception { /* ... */ }

?>