<?php

include '../container.php';

class Ledger {
}

class Invoice {
	var $ledger;
	
	function __construct(Ledger $ledger) {
		$this->ledger = $ledger;
	}
}

/**
 * example2.php
 * 
 * Invoice depends on Ledger. 
 */

$container = new Container();
$container->add('Invoice');
$container->add('Ledger');

$invoice = $container->getInstance('Invoice');

print_r($invoice);

?>
