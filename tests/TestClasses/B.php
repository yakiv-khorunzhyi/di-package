<?php

namespace tests\TestClasses;

class B
{
    protected $num;

    protected $str;

    public function __construct(int $num = 1, string $str = 'str')
    {
        $this->str = $str;
        $this->num = $num;
    }
}