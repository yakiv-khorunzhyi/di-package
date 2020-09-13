<?php

namespace tests\TestClasses;

class A
{
    protected $b;

    public function __construct(B $obj)
    {
        $this->b = $obj;
    }
}