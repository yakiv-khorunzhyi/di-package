<?php

namespace tests\TestClasses;

class SomeClass
{
    protected $a;

    public function __construct(A $obj)
    {
        $this->a = $obj;
    }
}