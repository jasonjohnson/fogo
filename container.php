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
			if($method->name == $this->prefix)
				continue;
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
		
		$instance = new $name;
		$dependencies = $this->components[$name];
		
		foreach($dependencies as $dependency) {
			if(in_array($name, $this->components[$dependency]))
				die("Circular dependency: {$name} <> {$dependency}");
			call_user_method("set{$dependency}", $instance, $this->getInstance($dependency));
		}
		
		$this->instances[$name] = $instance;
	}
}

?>