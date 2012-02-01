<?php

class Example { }

class ConstructorExample
{
    var $example;

    public function __construct(Example $example)
    {
        $this->example = $example;
    }

}

class Circular1
{
    var $circular2;

    public function __construct(Circular2 $circular2)
    {
        $this->circular2 = $circular2;
    }

}

class Circular2
{
    var $circular1;

    public function __construct(Circular1 $circular1)
    {
        $this->circular1 = $circular1;
    }
}

class UnresolvableDependency
{
    var $unresolvable;

    public function __construct(Unresolvable $unresolvable)
    {
        $this->unresolvable = $unresolvable;
    }
}

interface ExampleInterface
{
    function exampleMethod();
}

class ExampleImplementation implements ExampleInterface
{
    public function exampleMethod()
    {
        return true;
    }
}

class ExampleUsingInterface
{
    var $example;

    public function __construct(ExampleInterface $example)
    {
        $this->example = $example;
    }
}
