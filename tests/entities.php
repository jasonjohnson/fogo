<?php

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

class ConstructorExample {
	var $example;
	function __construct(Example $example) {
		$this->example = $example;
	}
}

class ConstructorExample2 {
	var $example;
	var $example2;
	function __construct(Example $example) {
		$this->example = $example;
	}
	
	function setExample2($example2) {
		$this->example2 = $example2;
	}
}

class Circular1 {
	var $circular2;
	function setCircular2($circular2) {
		$this->circular2 = $circular2;
	}
}

class Circular2 {
	var $circular1;
	function setCircular1($circular1) {
		$this->circular1 = $circular1;
	}
}

?>
