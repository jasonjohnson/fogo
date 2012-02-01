<?php

include '../container.php';

class Person
{
    var $name;
}

class Employee extends Person
{
    var $id;

    public function __construct() {
        
    }

}

/**
 * example1.php
 * 
 * Employee extends Person, yet Person isn't
 * required to be a part of our container.
 */
$container = new Container();
$container->add('Employee');

$employee = $container->getInstance('Employee');
$employee->id = 1;
$employee->name = "Jason";

print_r($employee);
