<?php

class Example {
}

class ConstructorExample {
	var $example;
	function __construct(Example $example) {
		$this->example = $example;
	}
}

class Circular1 {
	var $circular2;
	function __construct(Circular2 $circular2) {
		$this->circular2 = $circular2;
	}
}

class Circular2 {
	var $circular1;
	function __construct(Circular1 $circular1) {
		$this->circular1 = $circular1;
	}
}

?>
