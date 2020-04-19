<?php

namespace tests\TestClasses;

require_once(__DIR__ . '/A.php');

class SomeClass
{
    protected $a;

    public function __construct(A $obj)
    {
        $this->a = $obj;
    }
}