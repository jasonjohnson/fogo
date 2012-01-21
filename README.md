# Fogo #

## A lightweight dependency injection container for PHP. ##

Fogo uses PHP's built-in type hinting to inject dependencies directly into your constructors. In the example below, Fogo has wired Ledger to Invoice automatically.

```php
<?php

include 'container.php';

class Ledger {
}

class Invoice {
    var $ledger;
    
    function __construct(Ledger $ledger) {
        $this->ledger = $ledger;
    }
}

$container = new Container();
$container->add('Ledger');
$container->add('Invoice');

$invoice = $container->getInstance('Invoice');

?>
```
