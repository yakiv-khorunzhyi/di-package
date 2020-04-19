<?php

namespace Y\DI;

interface IContainer
{
    public function has($id): bool;

    public function get($id);
}