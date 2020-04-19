<?php

namespace tests\TestClasses;

require_once(__DIR__ . '/B.php');

class A
{
    protected $b;

    public function __construct(B $obj)
    {
        $this->b = $obj;
    }
}