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
	public $interfaces = array();
	public $instances = array();
	public $components = array();
	
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
					throw new ClassResolutionException("Could not resolve all dependencies for {$name}");
				}
			}
		}
		
		$this->components[$name] = $dependencies;
	}
	
	public function addImplementation($interface, $implementation) {
		$class = new ReflectionClass($implementation);
		
		if(!$class->implementsInterface($interface))
			throw new IncorrectImplementationException("{$implementation} does not implement {$interface}");
		
		$this->add($implementation);
		$this->interfaces[$interface] = $implementation;
	}
	
	public function addInstance($instance) {
		$class = new ReflectionObject($instance);
		$name = $class->getName();
		
		if(!isset($this->instances[$name]))
			$this->instances[$name] = $instance;
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
			if(array_key_exists($dependency, $this->interfaces))
				$dependency = $this->interfaces[$dependency];
			
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
class IncorrectImplementationException extends Exception { /* ... */ }

?>