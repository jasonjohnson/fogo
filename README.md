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

Fogo also supports interface injection, a staple feature of other containers supporting Inversion of Control (IoC). Example #5 goes into detail about this feature, but the example below touches on it briefly.

```php
<?php
include 'container.php';

interface Connection {

    function connect();
}

class MySQLConnection implements Connection {

    function connect() { /* ...connect! */
    }

}

class Controller {

    var $connection;

    function __construct(Connection $connection) {
        $this->connection = $connection;
    }

}

$container = new Container();
$container->add('Controller');
$container->addImplementation('Connection', 'MySQLConnection');

$controller = $container->getInstance('Controller');
?>
```
