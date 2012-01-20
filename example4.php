<?php

include 'container.php';

// This will hold all our Invoices, and any meta-data we need.
class Ledger {
	
}

// An Invoice, nothing more.
class Invoice {
	var $ledger;

	function setLedger($ledger) {
		$this->ledger = $ledger;
	}
}

/**
 * We're going to start with a manually wired version.
 */

// We'll create a new ledger.
$ledger = new Ledger();

// Now create a few Invoices. This is "manual injection" and it is fine
// but you can see it really won't scale (from a programmers point of view).
$invoice1 = new Invoice();
$invoice1->setLedger($ledger);

// Also, notice this is a different object. We have $invoice1 and $invoice2.
// If this contains critical business logic, what if they get out of sync?
$invoice2 = new Invoice();
$invoice1->setLedger($ledger);



/**
 * Now for the Dependency Injection container version.
 */

// Create a new "bucket" or "service." This will include references to
// all the classes which make up a unit of business logic. (Like creating
// invoices!)
$contaner = new Container();

// We aren't creating new classes, just adding their names to a list we
// can reference when we call for them later.
$container->add('Ledger');
$container->add('Invoice');

// Now, lets get that Invoice.
$invoice = $container->getInstance('Invoice');

// What just happened? Our container scanned the Invoice class, realized
// it needed a Ledger. It created a new Ledger object and "injected" it
// using our $invoice->setLedger($ledger) method for us. No manual wiring!

// Now, for the good part.
$ledger = $container->getInstance('Ledger');

// The Ledger we just asked for in the line above is THE EXACT SAME copy
// of the Ledger we gave to our Invoice. We never duplicate instances, so
// all of our business logic is perfectly intact!

// Think of it as spooling up an ecosystem, instead of wiring together
// a big piece of machinery.
