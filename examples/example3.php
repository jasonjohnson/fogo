<?php

include '../container.php';

class Ledger {
	
}

class Invoice {
	var $ledger;
	var $product;
	
	function __construct(Ledger $ledger, Product $product) {
		$this->ledger = $ledger;
		$this->product = $product;
	}
}

class Product {
	var $ledger;
	
	function __construct(Ledger $ledger) {
		$this->ledger = $ledger;
	}
}

/**
 * example3.php
 * 
 * Ledger depends on nothing;
 * Product depends on Ledger;
 * Invoice depends on both Ledger and Product. 
 */

$container = new Container();
$container->add('Invoice');
$container->add('Ledger');
$container->add('Product');

$invoice = $container->getInstance('Invoice');
$product = $container->getInstance('Product');

// The same instance, passed around inside our container.
echo spl_object_hash($invoice->ledger) . PHP_EOL;
echo spl_object_hash($product->ledger) . PHP_EOL;

// The container maintains an index of registered components and
// their depdenencies. It checks for circular associations.
print_r($container);

?>