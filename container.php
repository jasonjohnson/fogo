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
		$class = new ReflectionClass($name);
		$methods = $class->getMethods();
		
		foreach($methods as $method) {
			// Skip if we're looking at a method matching our prefix exactly.
			if($method->name == $this->prefix)
				continue;
			
			// Reflect the constructor parameters, looking for type hints.
			if($method->name == '__construct') {
				foreach($method->getParameters() as $parameter)
					$dependencies[] = $parameter->getClass()->name;
			}
			
			// Infer the type of the object to be injected by the remainder
			// of the method name.
			if(substr($method->name, 0, $this->prefixLength) == $this->prefix)
				$dependencies[] = substr($method->name, $this->prefixLength);
		}
		
		$this->components[$name] = $dependencies;
	}
	
	public function getInstance($name) {
		if(!isset($this->instances[$name]))
			$this->resolve($name);
		return $this->instances[$name];
	}
	
	private function resolve($name) {
		if(!isset($this->components[$name]))
			die("Cannot resolve class {$name}");
		
		$dependencies = $this->components[$name];
		$args = array();
		$class = new ReflectionClass($name);
		$constructor = null;
		
		try {
			$constructor = $class->getMethod('__construct');
		} catch(ReflectionException $e) {
			echo $e->getMessage() . PHP_EOL;
		}
		
		if($constructor) {
			foreach($constructor->getParameters() as $parameter)
				$args[] = $this->getInstance($parameter->getClass()->name);
		}
		
		$instance = $class->newInstanceArgs($args);
		
		foreach($dependencies as $dependency) {
			if(in_array($name, $this->components[$dependency]))
				die("Circular dependency: {$name} <> {$dependency}");
			
			// Attempt to use setter injection in case we missed something on
			// the constructor.
			$method = "set{$dependency}";
			if(method_exists($instance, $method))
				call_user_method($method, $instance, $this->getInstance($dependency));
		}
		
		$this->instances[$name] = $instance;
	}
}

?>