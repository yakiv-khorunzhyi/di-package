<?php

/**
 * @author       Yakiv Khorunzhyi
 * @copyright    2020
 * @license      MIT
 */

declare(strict_types=1);

namespace Y\DI;

interface IContainer
{
    /**
     * Check if entity exists by id.
     *
     * @param $id
     *
     * @return bool
     */
    public function has($id): bool;

    /**
     * The method returns an instance of the desired class by id.
     * If there is no instance of the class, then it is created.
     *
     * @param $id
     *
     * @return object|null
     * @throws \ReflectionException
     */
    public function get($id): ?object;
}