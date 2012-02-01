<?php

include '../container.php';

class Ledger
{
    var $invoiceFinder;

    public function __construct(InvoiceFinder $invoiceFinder)
    {
        $this->invoiceFinder = $invoiceFinder;
    }

    public function getOutstandingBalance()
    {
        $balance = 0.0;
        foreach ($this->invoiceFinder->findAll() as $invoice) {
            $balance += $invoice->amountDue;
        }
        return $balance;
    }

}

class InvoiceFinder
{
    var $invoices = array();

    public function __construct()
    {
        // Of course this data would be loaded from an exteral store by way
        // of a data mapper or access object, this is simply an example.
        $this->addInvoice(new Invoice(1, 123, 10.00, 'example1.php'));
        $this->addInvoice(new Invoice(2, 123, 12.00, 'example2.php'));
        $this->addInvoice(new Invoice(3, 123, 14.00, 'example3.php'));
        $this->addInvoice(new Invoice(4, 456, 37.00, 'example4.php'));
        $this->addInvoice(new Invoice(5, 456, 94.00, 'example5.php'));
    }

    public function addInvoice($invoice)
    {
        $this->invoices[] = $invoice;
    }

    public function findAll()
    {
        return $this->invoices;
    }

}

class Invoice
{
    var $id;
    var $customerId;
    var $amountDue;
    var $filePath;

    public function __construct($id, $customerId, $amountDue, $filePath)
    {
        $this->id = $id;
        $this->customerId = $customerId;
        $this->amountDue = $amountDue;
        $this->filePath = $filePath;
    }

    public function getRealPath()
    {
        $info = new SplFileInfo($this->filePath);
        return $info->getRealPath();
    }

    public function __toString()
    {
        return sprintf("<Invoice '%d', '%d', '%s'>", $this->id, $this->customerId, $this->filePath);
    }
}

// Notice I'm not even attempting to have our container manage Invoice. It is
// merely a data object which should be loaded from our InvoiceFinder.
$container = new Container();
$container->add('Ledger');
$container->add('InvoiceFinder');

$ledger = $container->getInstance('Ledger');

$balance = $ledger->getOutstandingBalance();
$allInvoices = $ledger->invoiceFinder->findAll();

echo "Outstanding Balance: " . $balance . PHP_EOL;

foreach ($allInvoices as $invoice) {
    // Inspect each object to see the provided path.
    echo $invoice . PHP_EOL;

    // Now lets get a real path using an SplFileInfo object.
    echo "\tReal Path:" . $invoice->getRealPath() . PHP_EOL;
}
