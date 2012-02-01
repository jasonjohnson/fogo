# Fogo #

## A lightweight dependency injection container for PHP. ##

Fogo uses PHP's built-in type hinting to inject dependencies directly into your constructors. In the example below, Fogo has wired Ledger to Invoice automatically.

```php
<?php
include 'container.php';

class Ledger { }

class Invoice
{
    private $ledger;

    public function __construct(Ledger $ledger)
    {
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

interface Connection
{
    public function connect();
}

class MySQLConnection implements Connection
{
    public function connect() { /* ...connect! */ }
}

class Controller
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }
}

$container = new Container();
$container->add('Controller');
$container->addImplementation('Connection', 'MySQLConnection');

$controller = $container->getInstance('Controller');
?>
```

Instance injection is also supported. You may need this if an external resource must be configured prior to injection.

```php
<?php
include 'container.php';

class Logger
{
    private $basePath;
    private $logLevel;
    
    public function __construct($basePath = '', $logLevel = 'warn')
    {
        $this->basePath = $basePath;
        $this->logLevel = $logLevel;
    }
}

class Controller
{
    private $logger;
    
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }
}

$logger = new Logger('../logs', 'error');
$container = new Container();
$container->add('Controller');
$container->addInstance($logger);

$controller = $container->getInstance('Controller');

?>
```
