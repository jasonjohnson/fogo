<?php

include '../container.php';

interface Connection
{
    public function connect();
    public function executeQuery();
    public function disconnect();
}

/**
 * Our various implementations of "Connection" which can be used as our 
 * implementation. 
 */
class MySQLConnection implements Connection
{
    public function connect() { /* ...stub... */ }
    public function disconnect() { /* ...stub... */ }
    public function executeQuery() { /* ...stub... */ }
}

class SqliteConnection implements Connection
{
    public function connect() { /* ...stub... */ }
    public function disconnect() { /* ...stub... */ }
    public function executeQuery() { /* ...stub... */ }
}

class DummyConnection implements Connection
{
    public function connect() { /* ...stub... */ }
    public function disconnect() { /* ...stub... */ }
    public function executeQuery() { /* ...stub... */ }
}

/**
 * Lastly, our controller depends on whichever Connection implementation
 * we care to inject. Perhaps MySQL for production, Sqlite for development
 * and Dummy for testing?
 */
class Controller
{
    var $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function debug()
    {
        echo get_class($this->connection);
    }
}

// Create out container the same way as our previous examples.
$container = new Container();
$container->add('Controller');

// First we specify which interface, then the implementation we want used. 
$container->addImplementation('Connection', 'MySQLConnection');

// Any of these will work as well with this example...
// $container->addImplementation('Connection', 'SqliteConnection');
// $container->addImplementation('Connection', 'DummyConnection');
// Get our instance and confirm which class was used.
$controller = $container->getInstance('Controller');
$controller->debug();
